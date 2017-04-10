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
# from scrapy.spider import BaseSpider

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
    """Setups item signal and run the spider"""
    #import os
    #os.environ.setdefault('SCRAPY_SETTINGS_MODULE', 'project.settings') #Must be at the top before other imports
    
    from scrapy.xlib.pydispatch import dispatcher
    from scrapy import signals
    from scrapy import settings
    from scrapy.contrib.spiders import CrawlSpider

    #from scrapy.crawler import LecSpider

    def catch_item(sender, item, **kwargs):
        print "Got:", item

    dispatcher.connect(catch_item, signal=signals.item_passed)

    # shut off log
    from scrapy.conf import settings
    settings.overrides['LOG_ENABLED'] = False

    # set up crawler
#     from scrapy.crawler import CrawlerProcess

    crawler = CrawlSpider(settings)
    crawler.configure()
#     crawler.install()
#     crawler.configure()

    # schedule spider
    # crawler.crawl(LecSpider())

    # start engine scrapy/twisted
    print "STARTING ENGINE"
    crawler.start()
    print "ENGINE STOPPED"


if __name__ == '__main__':
    main()


