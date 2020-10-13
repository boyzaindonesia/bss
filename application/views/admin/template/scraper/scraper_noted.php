<div class="row">
	<div class="col-sm-12">
		<div class="the-box">
			<h4 class="small-title">Cek Kondisi Website</h4>
			<p class="text-muted text-justify">
			http://www.websitecondition.com/home/index/9456<br/>
			</p>
		</div>
	</div>
	<div class="col-sm-12">
		<div class="the-box">
			<h4 class="small-title">STEP SCRAPER USERNAME INTAGRAM</h4>
			<p class="text-muted text-justify">
				https://www.instagram.com/vanillahijab/?__a=1<br/>
				https://www.instagram.com/graphql/query/?query_id=17851374694183129&variables={"id":"327487572","first":6000,"after":""}<br/>
				http://www.jsoneditoronline.org/<br/>
				http://www.convertcsv.com/json-to-csv.htm > JSON to Excel<br/><br/>

				copy 1 coloum username, kemudian pisahkan dgn ,<br/>
				generate via http://localhost/hacked/split_username_ig.php<br/>
				copy result ke auto_follow.rb
			</p>
		</div>
	</div>
	<div class="col-sm-12">
		<div class="the-box">
			<h4 class="small-title">Ajax Tokopedia</h4>
			<p class="text-muted text-justify">
			https://ace.tokopedia.com/search/v1/product?shop_id=1454973&ob=11&rows=5000&start=0&full_domain=www.tokopedia.com&scheme=https&device=desktop&source=shop_product<br/><br/>
			https://ace.tokopedia.com/search/product/v3?shop_id=1454973&ob=11&rows=5000&start=0&full_domain=www.tokopedia.com&scheme=https&device=desktop&source=shop_product<br/><br/>
			https://www.tokopedia.com/provi/check?pid=234079852<br/><br/>
			https://js.tokopedia.com/js/shoplogin?id=1454973<br/><br/>
			https://js.tokopedia.com/productstats/check?pid=234079852
			
			</p>
			<p class="text-muted text-justify">
			var prodCount = 0;<br/>
			$.ajax({<br/>
			    url: "https://ace.tokopedia.com/search/v1/product",<br/>
			    async : false,<br/>
			    data: {<br/>
			        q: keyword,<br/>
			        st: "product",<br/>
			        correction: false,<br/>
			        page: page,<br/>
			        fshop: 1,<br/>
			        // ob: 9,<br/>
			        sc: categories,<br/>
			        floc: location,<br/>
			        pmin: minPrice,<br/>
			        pmax: maxPrice,<br/>
			        wholesale: grosir,<br/>
			        rows: 100,<br/>
			        start: (page - 1) * 100,<br/>
			        full_domain: "www.tokopedia.com"<br/>
			    }<br/>
			}).done(function(data, textStatus, jqXHR ) {<br/>
			    prodCount = data.header.total_data;<br/>
			});<br/>
			return prodCount;<br/>
			</p>
		</div>
	</div>
</div>