
$(function(){
	"use strict";
	//console.log("script start");
	try{
		var title = $("title").eq(0).text(), url = $('link[rel="canonical"]').eq(0).attr("href") || document.URL.replace(/[?#].*$/g,''), icos, i, lurl, alen, temp, 
es = function(raw){
	return encodeURIComponent(raw).replace(/[!'()]/g, escape).replace(/\*/g, "%2A");
};
		icos = ["twitter-ico","facebook-ico","tumblr-ico","hatebu-ico","google-plus-ico"];
		for(i=0,alen=icos.length;i<alen;++i){
			temp = icos[i];
			if(temp == "twitter-ico"){
				lurl = "https://twitter.com/intent/tweet?text="+es(title)+"&url="+es(url);
			}else if(temp == "facebook-ico"){
				lurl = "http://www.facebook.com/share.php?u="+es(url);
			}else if(temp == "tumblr-ico"){
				lurl = "http://www.tumblr.com/share/link?url="+es(url)+"&name="+es(title);
			}else if(temp == "hatebu-ico"){
				lurl = "http://b.hatena.ne.jp/entry/"+es(url);
			}else if(temp == "google-plus-ico"){
				lurl = "https://plus.google.com/share?url="+es(url);
			}else{
				continue;
			}
			temp = $("#"+temp);
			if(temp.length && lurl){
				temp.find("a").eq(0).attr("href",lurl);
			}else{
				throw "no elem OR no url";
			}
		}
	}catch(e){
	//console.log(e);
	}
	
	var urls = [
		"s.ytimg.com",
		"pbs.twimg.com",
	];

	

	if(/^https?:\/\/srytk.com\/\d+\.html[^\/]*$/.test(document.URL)){
		var re = /\.(jpe?g|png|gif|webp|html?)$/;
		var re_thumb = /-\d{2,3}x\d{2,3}\.(jpe?g|png|gif|webp)$/;
		var scheme = window.location.protocol;
	
		//記事内内部リンク
		$("#content .entry-content a[href^='"+scheme+"//srytk.com/']").each(function(ind){
			if(ind > 4){
				return false;
			}

			var internalLinkURL = $(this).attr("href");
			if(re.test(internalLinkURL)){
				urls.push(internalLinkURL);
			}
		});

		//関連記事のリンク
		$("#related-posts-by-taxonomy-2 a[href^='"+scheme+"//srytk.com/']").each(function(ind){
			if(ind > 10){
				return false;
			}

			var postLink = $(this).attr("href");
			if(re.test(postLink)){
				urls.push(postLink);
				var thumbnailImageURL = $(this).find("img[src^='"+scheme+"://srytk.com/']").eq(0).attr("src");
				if(re_thumb.test(thumbnailImageURL)){
					urls.push(thumbnailImageURL.replace(re_thumb,".$1")); //アイキャッチのオリジナルサイズを追加
				}
			}
		});
	}

	//新着記事のhtml
	$("#recent-posts-plus-2 .RecentPostsPlus a[href^='"+scheme+"//srytk.com/']").each(function(ind){
		if(ind>3){
			return false;
		}

		var internalLinkURL = $(this).attr("href");
		if(re.test(internalLinkURL)){
			urls.push(internalLinkURL);
		}
	});



	for(var i=0,l=urls.length,hElem = $("head").eq(0);i<l;++i){
		var elem = $("<link>");
		if(urls[i].indexOf('/') === -1){
			//dns-prefetch
			elem.attr({"rel":"dns-prefetch","href":"//"+urls[i]});
		}else if(urls[i].indexOf('srytk.com') !== -1){
			//prefetch
			elem.attr({"rel":"prefetch","href":urls[i]})
		}else{
			continue;
		}

		elem.appendTo(hElem);
	}

	//insert favicon in anchor
	/*
	$(".entry-content a:not(:has(*))").each(function(){
		$("<img>").attr({src:"https://www.google.com/s2/favicons?domain="+this.hostname}).addClass("favicon-img").prependTo($(this));
	});
	*/

	//click youtube thumbnail link to insert embed player
	$('.site-content').find('figure').find('a[href^="https://www.youtube.com/"]').each(function(){
		$(this).on('click', function(ev){
			var anc = $(this);
			var vidId = /[?&]v=([^?&]+)/.exec(this.href)[1];
			if(vidId){
				ev.preventDefault();
			}else{
				return false;
			}
			anc.css("display","none");
			$("<iframe>").attr({
				src:'//www.youtube-nocookie.com/embed/'+vidId+'?autoplay=1',
				frameborder:"0",
				width:"640",
				height:"360",
				allowfullscreen:"true"
			}).insertBefore(anc);
		})
	});


});



