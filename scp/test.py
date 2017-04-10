#!/usr/local/bin/python2.7

# Gregory Crawl Script
# 
# Note: the `crawler.start()` can't be called more than once due twisted's reactor limitation.

# enable debugging
import sys
import cgitb
cgitb.enable()

print "Content-Type: text/plain;charset=utf-8"
print

print sys.version_info

print "Hello World!"

