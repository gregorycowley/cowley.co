#!/bin/sh
#
#	trojan-scan.sh	- Trojan Scan: scan for unknown ports/processes listening to network
#
#	$Id: trojan-scan.sh,v 1.22 2013/04/19 21:16:34 jeroend Exp $
#
#	Copyright (c) 2005,2007,2011,2013 Derks.IT / Jeroen Derks
#
#	Licensed under the Apache License, Version 2.0 (the "License");
#	you may not use this file except in compliance with the License.
#	You may obtain a copy of the License at
#
#	http://www.apache.org/licenses/LICENSE-2.0
#
#	Unless required by applicable law or agreed to in writing, software
#	distributed under the License is distributed on an "AS IS" BASIS,
#	WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
#	See the License for the specific language governing permissions and
#	limitations under the License.
#
#	vim: set ts=4 sw=4 sts=4 noexpandtab bs=2:
#

PATH=:/sbin:/usr/sbin:/bin:/usr/bin:/usr/local/sbin:/usr/local/bin:

AWK="awk"
BASENAME="basename"
CAT="cat"
CP="cp"
CUT="cut"
DATE="date"
HEAD="head"
LS="ls"
MAIL="mail"
PS="ps"
RM="rm"
SORT="sort"
TAIL="tail"
UNIQ="uniq"
case `uname` in
	OpenBSD)
		GREP="/usr/bin/grep"
		HOSTNAME="/bin/hostname"
		LSOF="/usr/local/sbin/lsof"
		MD5="/bin/md5"
		MKTEMP="/usr/bin/mktemp"
		SED="/usr/bin/sed"
		BASH="/usr/local/bin/bash"

		POS_PROTO=8
		POS_CONN=9
		TXT_PATTERN='s@^.* \([^ ]\+\)$@\1@g'
		;;

	*[bB][sS][dD])
		GREP="/usr/bin/grep"
		HOSTNAME="/bin/hostname"
		LSOF="/usr/local/sbin/lsof"
		MD5="/sbin/md5 -q"
		MKTEMP="/usr/bin/mktemp"
		SED="/usr/bin/sed -E"
		BASH="/usr/local/bin/bash"

		POS_PROTO=8
		POS_CONN=9
		TXT_PATTERN='s@^.* \([^ ]\+\)$@\1@g'
		;;

	Darwin)
		GREP="/usr/bin/grep"
		HOSTNAME="/bin/hostname"
		LSOF="/usr/sbin/lsof"
		MD5="/sbin/md5"
		MKTEMP="/usr/bin/mktemp"
		SED="/usr/bin/sed"
		BASH="/bin/bash"

		POS_PROTO=8
		POS_CONN=9
		TXT_PATTERN='s@^.* \([^ ]\+\)$@\1@g'
		;; 

	*)
		GREP="/bin/grep"
		HOSTNAME="/bin/hostname -f"
		LSOF="/usr/sbin/lsof"
		MD5="/usr/bin/md5sum"
		MKTEMP="/bin/mktemp"
		SED="/bin/sed"
		BASH="/bin/sh"

		POS_PROTO=7
		POS_CONN=8
		TXT_PATTERN='s@^.* \([^ ]\+\)$@\1@g'
		;;
esac


VERSION=1.5.0
PROG=`$BASENAME $0`
CONFIG_FILE=/etc/trojan-scan/trojan-scan.conf
ALLOWED=dummy
RECIPIENTS=root
SUBJECT="[`hostname -s`] Trojan Scan report"
DEBUG=:
debug=false
verbose=false
noemail=false
full=false
date=`$DATE +"%Y/%b/%d %H:%M"`

#
#	Initialize temporary file handling
#
umask 077
TMP_FILE=`$MKTEMP /tmp/$PROG.tmp-1.XXXXXXXX`
result=$?
if [ x"$TMP_FILE" = x ]; then
	exit $result
fi
PS_FILE=`$MKTEMP /tmp/$PROG.tmp-2.XXXXXXXX`
result=$?
if [ x"$PS_FILE" = x ]; then
	exit $result
fi
OUT_FILE=`$MKTEMP /tmp/$PROG.tmp-3.XXXXXXXX`
result=$?
if [ x"$OUT_FILE" = x ]; then
	exit $result
fi

trap "res=$?; $RM -rf $TMP_FILE $PS_FILE $OUT_FILE; exit $res" 0 1 2 3 4 5 6 7 8 9 10 11 12 13 14 15


#
#	Match two format with value
#
match()
{
	case "$1" in
		!@any@!|$2|{$2,*}|{*,$2}|{*,$2,*})
			$DEBUG "match: '$1' - '$2': ok"
			return 0
			;;
		*)
			$DEBUG "match: '$1' - '$2': FAILED"
			return 1
			;;
	esac
}


#
#	Determine inbound/outbound ports from protocol and connection
#
get_ports()
{
	proto="$1"
	conn="$2"

	test="$proto:$conn"
	case "$test" in
		ICMP:*)		# ICMP
			OLDIFS="$IFS"
			IFS=":"
			set - `echo "$conn" | sed -e 's@\*@X@g'`
			if [ "X" = "$2" ]; then
				port_in=
			else
				port_in="$2"
			fi
			port_out=
			IFS="$OLDIFS"
			;;
		*:\[*\]:*-\>\[*\]:*)	# TCP/UDP/IPv6 'connection'
			OLDIFS="$IFS"
			IFS="]-"
			set - `echo "$conn" | sed -e 's@\*@X@g'`
			port_in="`echo \"$2\" | cut -c2-`"
			port_out="`echo \"$4\" | cut -c2-`"
			IFS="$OLDIFS"
			;;
		*:*-\>*:*)	# TCP/UDP 'connection'
			OLDIFS="$IFS"
			IFS=":-"
			set - `echo "$conn" | sed -e 's@\*@X@g'`
			port_in="$2"
			port_out="$4"
			IFS="$OLDIFS"
			;;
		UDP:*|ICMP:*|ICMPV6:*)		# UDP nor ICMP have connections, assume inbound port
			OLDIFS="$IFS"
			IFS=":"
			set - `echo "$conn" | sed -e 's@\*@X@g'`
			port_in="$2"
			port_out=
			IFS="$OLDIFS"
			;;
		TCP:\[*\]:*)	# TCP/IPv6 listening
			OLDIFS="$IFS"
			IFS="]-"
			set - `echo "$conn" | sed -e 's@\*@X@g' | cut -c2-`
			port_in="$2"
			port_out=
			IFS="$OLDIFS"
			;;
		TCP:*:*)	# TCP listening
			OLDIFS="$IFS"
			IFS=":-"
			set - `echo "$conn" | sed -e 's@\*@X@g'`
			port_in="$2"
			port_out=
			IFS="$OLDIFS"
			;;
		*)		# unknown
			echo "ERROR: Uknown connection string '$test', contact author"
			continue 2

	esac
	$DEBUG "port_in='$port_in'"
	$DEBUG "port_out='$port_out'"

	export port_in port_out
}


#
#	Generate default configuration	
#
generate_config()
{
	(
		echo '#!/bin/sh'
		echo '#'
		echo '#	trojan-scan.conf	- Trojan Scan configuration file'
		echo '#'
		echo "#	Generated by $PROG $VERSION" at `$DATE`
		echo '#'
		echo '#	Copyright (c) 2005,2007,2011 Derks.IT / Jeroen Derks'
		echo '#'
		echo '#	Licensed under the Apache License, Version 2.0 (the "License");'
		echo '#	you may not use this file except in compliance with the License.'
		echo '#	You may obtain a copy of the License at'
		echo '#'
		echo '#	http://www.apache.org/licenses/LICENSE-2.0'
		echo '#'
		echo '#	Unless required by applicable law or agreed to in writing, software'
		echo '#	distributed under the License is distributed on an "AS IS" BASIS,'
		echo '#	WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.'
		echo '#	See the License for the specific language governing permissions and'
		echo '#	limitations under the License.'
		echo '#'

		# determine program paths
		echo
		echo '#'
		echo '#	 Program paths'
		echo '#'
		for prog in awk basename bash cat cp cut date head ls mail ps rm sort tail grep hostname lsof md5 mktemp sed
		do
			if path=`which $prog 2> /dev/null`; then
				case "$prog" in
					tail)
							# check whether -n flag can be used
							if tail -h 2>&1 | grep -e -n > /dev/null; then
								path="$path -n"
							elif tail --help 2>&1 | grep -e -n > /dev/null; then
								path="$path -n"
							fi
							;;
				esac
			else
				case "$prog" in
					md5)
						path=`which md5sum 2>/dev/null`
						;;
				esac
				if [ -z "$path" ]; then
					echo "WARNING: command '$prog' is not found, $PROG will probably not work!" 1>&2
				fi
			fi

			echo `echo $prog | tr '[[a-z]]' '[[A-Z]]'`=\"$path\"
			export $prog="$path"
		done

		# different POS_xxx in lsof output
		if $lsof -Pni | $head -1 | $grep 'SIZE/OFF' > /dev/null 2>&1; then
			export POS_PROTO=8 POS_CONN=9
			echo
			echo '#'
			echo '#	 POS_PROTO - protocol column in lsof output'
			echo '#	 POS_CONN  - connection column in lsof output'
			echo '#'
			echo "POS_PROTO=\"$POS_PROTO\""
			echo "POS_CONN=\"$POS_CONN\""
		fi

		# other variables
		echo
		echo '#'
		echo '#	 RECIPIENTS	- whitespace separated list of email report recipients'
		echo '#'
		echo "RECIPIENTS=\"$USER\""

		echo
		echo '#'
		echo '#	SUBJECT		- email report subject'
		echo '#'
		echo SUBJECT=\"[`$HOSTNAME -s`] Trojan Scan report\"

		echo
		echo '#'
		echo '#	LSOF_MD5	- MD5 checksum of lsof program for integrity checking'
		echo '#'
		echo "LSOF_MD5=\"`$MD5 $lsof`\""

		# determine allowed rules
		echo
		echo '#'
		echo '#	ALLOWED		- for defining allowed processes, ports and users, configuration values consist of:'
		echo '#				- $ALLOWED							used to continue ALLOWED definition'
		echo '#				- processname:protocol:in portlist:out portlist:userlist	separated by whitespace'
		echo '#				  where portlist, userlist can also be !@any@! for any port or user'
		echo '#'
		$DEBUG "$lsof -Pni | $tail +2 | $sort -u | $AWK '{print \$1, \$3, $'${POS_PROTO}', $'${POS_CONN}';}' | $sort -u | $UNIQ -u"
		$lsof -Pni | $tail +2 | $sort -u | $awk '{print $1, $3, $'${POS_PROTO}', $'${POS_CONN}';}' | $sort -u | $UNIQ -u |
			while read name user proto conn
			do
				get_ports "$proto" "$conn"

				$DEBUG "name=$name"
				$DEBUG "user=$user"
				$DEBUG "proto=$proto"
				$DEBUG "conn=$conn"
				$DEBUG "port_in=$port_in"
				$DEBUG "port_out=$port_out"


				if [ x"" != x"$port_in" -a x"" != x"$port_out" ]; then
					if [ "$port_in" -lt 1024 -a "$port_out" -ge 1024 ]; then
						port_out="!@any@!"
					elif [ "$port_out" -lt 1024 -a "$port_in" -ge 1024 ]; then
						port_in="!@any@!"
					fi
				fi

				[ -z "$port_in" ] && export port_in="!@any@!"
				[ -z "$port_out" ] && export port_out="!@any@!"

				echo ALLOWED=\"\$ALLOWED "$name:$proto:$port_in:$port_out:$user\""
			done | $sort -t : -k 1,5 -u | $UNIQ -u
	) > $OUT_FILE
	
	res=$?
	if [ $res != 0 ]; then
		exit $res
	elif [ '' = "$1" -o x'-' = x"$1" ]; then
		$CAT $OUT_FILE
	else
		if [ -f "$1" ]; then
			echo -n "Are you sure you want to overwrite $1 with a new configuration? [y/N] "
			read answer
			case "$answer" in
				y|Y|[yY][eE][sS])
					# continue
					echo Yes, configuration file wil be overwritten.
					;;
				*)
					# abort
					echo No, configuration file not written.
					exit 2
					;;
			esac
		fi
		$CP $OUT_FILE "$1" && echo "Wrote configuration file $1."
	fi

	exit 0
}

#
#	Check integrity of lsof program
#
integrity()
{
	md5=`$MD5 $LSOF | $SED -e 's/^.*\([0-9a-f]{32}\).*$/\\1/g'`
	if [ -z "${LSOF_MD5}" ]; then
		$DEBUG "saving MD5 of lsof program for integrity checking"
		echo "LSOF_MD5=\"$md5\"" >> "$CONFIG_FILE"
	elif [ x"$md5" != x"$LSOF_MD5" ]; then
		$DEBUG "lsof integrity check FAILED"
		if $noemail; then
			echo "$PROG: lsof integrity check FAILED!" 1>&2
		else
			echo "$PROG: lsof integrity check FAILED!" | $MAIL -s "$SUBJECT $date INTEGRITY FAILURE!" $RECIPIENTS
		fi
		exit 3
	else
		$DEBUG "lsof integrity check OK"
	fi
}

#
#	Scan for trojans
#
scan()
{
	# determine whether to show walker
	walker=false
	if $noemail || $verbose; then
		if $debug; then :; else
			walker=true
			token='-'
		fi
	fi

	$DEBUG "ALLOWED=${ALLOWED}"

	# get network connections and processes
	$DEBUG "$LSOF -Pni" 1>&2
	$LSOF -Pni | $TAIL +2 | $SORT -u > $TMP_FILE
	$DEBUG "$PS awxo 'pid ruser rgid tty pri ni stat %cpu state %mem rss vsz command'" 1>&2
	$PS awxo 'pid ruser rgid tty pri ni stat %cpu state %mem rss vsz command' | $TAIL +2 > $PS_FILE

	count=0
	$CAT $TMP_FILE | $AWK '{print $1, $2, $3, $'${POS_PROTO}', $'${POS_CONN}';}' | $SORT -u | $UNIQ -u |
		(
			pids=

			while read name pid user proto conn
			do
				get_ports "$proto" "$conn"

				$DEBUG "name='$name'" 1>&2
				$DEBUG "pid='$pid'" 1>&2
				$DEBUG "proto='$proto'" 1>&2
				$DEBUG "conn='$conn'" 1>&2
				$DEBUG "user='$user'" 1>&2
				$DEBUG "port_in='$port_in'" 1>&2
				$DEBUG "port_out='$port_out'" 1>&2

				val="$name:$proto:$port_in:$port_out:$user"
				$DEBUG "checking val='$val'" 1>&2
				if $walker; then
					echo -n "
checking '$val' ...                            
" 1>&2
				fi

				# check inbound ports
				if [ -z "$port_in" ]; then
					unknown=false
				else
					unknown=true
					for format in $ALLOWED
					do
						case "$format" in
							dummy) continue;;
						esac

						$DEBUG "format='$format'"
						OLDIFS="$IFS"
						IFS=:
						set - $format
						f_name="$1"
						f_proto="$2"
						f_port_in="$3"
						f_port_out="$4"
						f_user="$5"
						IFS="$OLDIFS"

						$DEBUG "f_name='$f_name'"
						$DEBUG "f_proto='$f_proto'"
						$DEBUG "f_port_in='$f_port_in'"
						$DEBUG "f_port_out='$f_port_out'"
						$DEBUG "f_user='$f_user'"

						if match "$f_name" "$name"; then 
							if match "$f_proto" "$proto"; then 
								if match "$f_user" "$user"; then 
									if [ -z "$port_in" ] || match "$f_port_in" "$port_in"; then 
										if [ -z "$port_out" ] || match "$f_port_out" "$port_out"; then 
											unknown=false
											break
										fi
									fi
								fi
							fi
						fi
					done
				fi

				if $unknown; then
					val="`echo \"$val\" | $SED -e 's#:X:#:\!\@any\@\!:#g' | $SED -e 's#::#:\!\@any\@\!:#g'`"
					$DEBUG "UNKNOWN: $val" 1>&2
					$DEBUG "prog=\`$LSOF -p $pid | $GREP -F \" txt \" | $SED -e \"${TXT_PATTERN}\" | $HEAD -1\`"
					prog=`$LSOF -p $pid | $GREP -F " txt " | $AWK '{ print $9; }' | $HEAD -1`
					$DEBUG "prog=$prog"
					echo "=============================================================================="
					echo "UNKNOWN:	$prog[$pid]	($val)"
					case "$pids" in
						*\ $pid:*)
							;;
						*)
							export pids="$pids $pid:$prog"
							;;
					esac
					echo "------------------------------------------------------------------------------"
					echo "ls:   `$LS -ald "$prog" 2> /dev/null`"
					echo "ps:   "`$GREP -E "^ *$pid " $PS_FILE`
					echo lsof:
					$GREP -E "^$name  *$pid  *$user.*$conn" "$TMP_FILE"
					if $full; then
						if kill -0 $pid; then
							echo lsof process:
							$LSOF -p $pid
						else
							echo process with PID $pid no longer running
						fi
					fi
					echo
				else
					$DEBUG "OK: $val" 1>&2
				fi
			done
		) > "$OUT_FILE"
	$walker && echo -n "
                                                                     
"
	[ ! -s "$OUT_FILE" ]
}

#
#	Program usage
#
usage()
{
	$CAT << __EOF__
$PROG v$VERSION - Copyright (c) 2005,2007,2011 Derks.IT / Jeroen Derks
usage:	$PROG [-d] [-F] [-n] [-v] [-x] [-C file]
		-d	debug mode
		-F	full output
		-n	do not send email
		-v	verbose mode
		-x	shell debug
		-C	generate default configuration (use - for stdout)
__EOF__
	exit 1
}


#
#	Process command line parameters
#
if [ $# != 0 ] ; then
	while [ "x$1" != x ]
	do
		case "$1" in
			-C)			[ $# -gt 1 ] && generate_config "$2" || usage; exit $?;;
			-d)			debug=true; export DEBUG=echo; shift;;
			-F)			full=true; shift;;
			-n)			noemail=true; shift;;
			-v)			verbose=true; shift;;
			-x)			set -x; shift;;
			*)			usage;;
		esac
	done
fi

# read configuration
[ -s "$CONFIG_FILE" ] && . "$CONFIG_FILE"
$DEBUG "ALLOWED=$ALLOWED" 1>&2
$DEBUG "RECIPIENTS=$RECIPIENTS" 1>&2
$DEBUG "SUBJECT=$SUBJECT" 1>&2
$DEBUG "LSOF_MD5=$LSOF_MD5" 1>&2

#
#	Main program
#
integrity
if scan; then :; else
	# trojans found, report
	$noemail || $DEBUG "mail -s '$SUBJECT' $RECIPIENTS"
	(
		echo "The following (probable) trojans where found:"
		echo
		$CAT "$OUT_FILE"
		echo
		echo Full list of open network files:
		$CAT "$TMP_FILE"
		echo
		echo Full process list:
		$CAT "$PS_FILE"
		echo
		echo --
		echo This email was automagically generated on `$HOSTNAME`
		echo at `$DATE` by $PROG $VERSION
	) | $BASH -c "`$noemail && echo $CAT || echo $MAIL -s \\\"$SUBJECT $date\\\" $RECIPIENTS`"
	exit 2
fi
