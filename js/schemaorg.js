$(function(){
	"use strict";
	var ua = window.navigator.userAgent;
	if(	ua.indexOf("bot") !== -1 || ua.indexOf("http") !== -1){
		//bots
		var elems = [];
		var body = $('body');
		var breadcrumb = $('aside > .breadcrumbs');
		var relatedPosts = $('aside.related_posts_by_taxonomy figure');
		var article = $('#content > article');
		var i,index,val,count;
		elems.push(
			{
				elem : body,
				props : {
					itempscope : 'itemscope',
					itemtype : 'https://schema.org/Blog'
				}
			},
			{
				elem : article,
				props : {
					itemscope : 'itemscope',
					itemtype : 'https://schema.org/BlogPosting',
				}
			},
			{
				//記事のボディ
				elem : article.find('.entry-content'),
				props : {
					itemprop : 'articleBody'
				}
			},
			{
				//公開日時
				elem : article.find('.entry-meta time.entry-date'),
				props : {
					itemprop : 'datePublished'
				}
			},
			{
				//著者 編集
				elem : article.find('.entry-meta span.author'),
				props : {
					itemprop : 'author editor',
				}
			},
			{
				//ジャンル
				elem : article.find('entry-meta span.cat-links'),
				props : {
					itemprop : 'genre'
				}
			},
			{
				//見出し
				elem : article.find('h1,h2,h3,h4'),
				props : {
					itemprop : 'headline'
				}
			},
			{
				//キーワード
				elem : article.find('.tag-links>a'),
				props : {
					itemprop : 'keywords',
				}
			}
		);

		if(body.hasClass('single-post')){
			//個別記事である
			
			if(breadcrumb.length){
				//パンくずあり
				elems.push(
					{
						elem : breadcrumb,
						props : {
							itemscope : "itemscope",
							itemtype : "https://schema.org/WebPage",
							itemprop : "breadcrumb"
						}
					}
				);
			}

			if(article.length){
				//記事あり
				
				if(relatedPosts.length){
					//関連記事あり
					elems.push(
						{
							elem : relatedPosts,
							props : {
								itemscope : 'itempscope',
								itemptype : 'https://schema.org/WebPage'
							}
						},
						{
							elem : relatedPosts.find("a"),
							props : {
								itemprop : "relatedLink url"
							}
						},
						{
							elem : relatedPosts.find("figcaption"),
							props : {
								itemprop : "name"
							}
						}
					);
				}
				
				if(article.hasClass('.has-post-thumbnail')){
					//アイキャッチ有り
					elems.push(
						{
							elem : article.find('.post-thumbnail>img'),
							props : {
								itemprop : "image thumbnailUrl"
							}
						}
					);
				}
			}
		}


		if(elems.length){
			count = elems.lenth;
			for(i=0;i<count;++i){
				val = elems[i];

				if(val.elem.lenth){
					for (key in val.props){
						if(val.props.hasOwnProperty(key)){
							val.elem.attr(key, val.props[key]);
						}
					}
				}
			}
		}

			


		
	}else{
		return;
	}
});
