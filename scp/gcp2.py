# Gregory Crawl Script
# 
# Note: the `crawler.start()` can't be called more than once due twisted's reactor limitation.

#!/usr/bin/env python
# -*- coding: utf-8 -*-
# author: Gregory Cowley
#
# Changelog: 
#     10/23/2013 - launched


from scrapy.contrib.loader import XPathItemLoader
from scrapy.item import Item, Field
from scrapy.selector import HtmlXPathSelector
from scrapy.contrib.linkextractors.sgml import SgmlLinkExtractor
from scrapy.contrib.spiders import CrawlSpider, Rule


class LecorpioItem(Item):
    """Lecorpio Data Item"""
    status = Field()
    link = Field()
    title = Field()
    description = Field()
    keywords = Field()
    meta = Field()

            
class LecSpider(CrawlSpider):
    """Our ad-hoc spider"""
    name = 'lec'
    allowed_domains = ['lecorpio.com']
    start_urls = ['http://www.lecorpio.com/']
    
    handle_httpstatus_list = [404,500]
    
    rules = (
        Rule(SgmlLinkExtractor(), callback='parse_item', follow=True),
    )

    def parse_item(self, response):
        hxs = HtmlXPathSelector(response)
        i = LecorpioItem()
        i['status'] = response.status
        i['link'] = response.url
        i['title'] = hxs.select('/html/head/title/text()').extract()
        i['description'] = hxs.select('//meta[@name="description"]').extract()
        i['keywords'] = hxs.select('//meta[@name="keywords"]').extract()
        i['meta'] = response.meta
        #i['text'] = hxs.select('//body//text()').extract()
        return i


class LecorpioPipeline(object):
    def process_item(self, item, spider):
        return item


def main():
    from twisted.internet import reactor
    from scrapy.crawler import Crawler
    from scrapy import log, signals
    from testspiders.spiders.followall import FollowAllSpider
    from scrapy.utils.project import get_project_settings

    spider = FollowAllSpider(domain='scrapinghub.com')
    settings = get_project_settings()
    crawler = Crawler(settings)
    crawler.signals.connect(reactor.stop, signal=signals.spider_closed)
    crawler.configure()
    crawler.crawl(spider)
    print "STARTING ENGINE"
    
    crawler.start()
    log.start()
    reactor.run() # the script will block here until the spider_closed signal was sent
    
    # start engine scrapy/twisted
    
    # reactor.run() # the script will block here
    print "ENGINE STOPPED"


if __name__ == '__main__':
    main()


