<body class="shop page-layout-left-sidebar">
		
		<div id="wrapper" class="wide-wrap">
			
			<div class="content-container">
				<div class="container">
					<div class="row">
						<div class="col-md-12 main-wrap">
							<div class="main-content">
								<div class="row">
									<div class="col-sm-12">
										<div class="posts" data-paginate="page_num" data-layout="default">
											<div class="posts-wrap posts-layout-default">
											<?php
												$no = 1 + $this->uri->segment(3);
												foreach($article as $art){
											?>
												<article class="hentry">
													<div class="hentry-wrap">
														<div class="entry-featured">
															<a href="#">
																<img width="700" height="350" src="<?=base_url('assets/backend/products/'.$art->img)?>" alt="blog-4"/>
															</a>
														</div>
														<div class="entry-info">
															<div class="entry-header">
																<h2 class="entry-title">
																	<a href="#">
																		<?=$art->title?>
																	</a>
																</h2>
																<div class="entry-meta icon-meta">
																	<span class="meta-date">
																		<time datetime="2015-08-11T06:27:49+00:00">
																			<i class="fa fa-clock-o"></i><?=date('D, M Y', strtotime($art->created_at))?>
																		</time>
																	</span>
																</div>
															</div>
															<div class="entry-content">
																<?=$art->description ?>	
															</div>
															
														</div>
													</div>
												</article>
											<?php } ?>
											</div>
											
										</div>
									</div>
								</div>
							</div>
						</div>
						
					</div>
				</div>
			</div>
		</div>
		
</body>