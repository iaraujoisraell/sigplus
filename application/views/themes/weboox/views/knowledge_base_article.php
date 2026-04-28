<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<script>
    $(function(){
        $('img').addClass('img-responsive');
    });
</script>

<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-grid--stretch">
	<div class="kt-container kt-body  kt-grid kt-grid--ver" id="kt_body">
		<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

				<!-- begin:: Subheader -->
				<div class="kt-subheader   kt-grid__item" id="kt_subheader">
					<div class="kt-subheader__main">
						<h3 class="kt-subheader__title">
							<?php echo _l('knowledge_base'); ?>
						</h3>
					</h3>
					<div class="kt-subheader__breadcrumbs">
						<a href="<?php echo site_url('clients') ?>" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
						<span class="kt-subheader__breadcrumbs-separator"></span>
						<a href="<?php echo site_url('clients') ?>" class="kt-subheader__breadcrumbs-link">
							Dashboard	                    </a>
							<span class="kt-subheader__breadcrumbs-separator"></span>
							<a href="<?php echo site_url('knowledge-base') ?>" class="kt-subheader__breadcrumbs-link">
								<?php echo _l('knowledge_base'); ?>    </a>
							</div>
						</div>
						<div class="kt-subheader__toolbar">
							<div class="kt-subheader__wrapper">
								<a href="<?php echo site_url('clients/tickets'); ?>" class="btn kt-subheader__btn-secondary">
									<?php echo _l('support'); ?>
								</a>
								<div class="dropdown dropdown-inline" data-toggle="kt-tooltip" title="<?php echo _l('calendar'); ?>" data-placement="top">
									<a href="<?php echo site_url('clients/calendar'); ?>" class="btn btn-danger kt-subheader__btn-options" aria-haspopup="true" aria-expanded="false">
										<?php echo _l('calendar'); ?>
									</a>
									<div class="dropdown-menu dropdown-menu-right">
										<a class="dropdown-item" href="#"><i class="la la-plus"></i> New Product</a>
										<a class="dropdown-item" href="#"><i class="la la-user"></i> New Order</a>
										<a class="dropdown-item" href="#"><i class="la la-cloud-download"></i> New Download</a>
										<div class="dropdown-divider"></div>
										<a class="dropdown-item" href="#"><i class="la la-cog"></i> Settings</a>
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- end:: Subheader -->

		</div>
	</div>
</div>


	<div class="kt-container  kt-grid__item kt-grid__item--fluid" style="margin-bottom: 60px;">
		<div class="row">
			<div class="col-lg-12">
				<div class="kt-portlet" id="kt_portlet">
					<div class="kt-portlet__body"><?php echo validation_errors('<div class="alert alert-danger text-center">', '</div>'); ?>
						<div class="panel_s">
							<div class="panel-body">
								<div class="row kb-article">
									<div class="col-md-<?php if(count($related_articles) == 0){echo '12';}else{echo '8';} ?>">
										<h1 class="bold no-mtop kb-article-single-heading"><?php echo $article->subject; ?></h1>
										<hr class="no-mtop" />
										<div class="mtop10 tc-content kb-article-content">
											<?php echo $article->description; ?>
										</div>
										<hr />
										<h4 class="mtop20"><?php echo _l('clients_knowledge_base_find_useful'); ?></h4>
										<div class="answer_response"></div>
										<div class="btn-group mtop15 article_useful_buttons" role="group">
											<input type="hidden" name="articleid" value="<?php echo $article->articleid; ?>">
											<button type="button" data-answer="1" class="btn btn-success"><?php echo _l('clients_knowledge_base_find_useful_yes'); ?></button>
											<button type="button" data-answer="0" class="btn btn-danger"><?php echo _l('clients_knowledge_base_find_useful_no'); ?></button>
										</div>
									</div>
									<?php if(count($related_articles) > 0){ ?>
										<div class="visible-xs visible-sm">
											<br />
										</div>
										<div class="col-md-4">
											<h4 class="bold no-mtop h3 kb-related-heading"><?php echo _l('related_knowledgebase_articles'); ?></h4>
											<hr class="no-mtop" />
											<ul class="mtop10 articles_list">
												<?php foreach($related_articles as $relatedArticle) { ?>
													<li>
														<h4 class="article-heading article-related-heading">
															<a href="<?php echo site_url('knowledge-base/article/'.$relatedArticle['slug']); ?>">
																<?php echo $relatedArticle['subject']; ?>
															</a>
														</h4>
														<div class="text-muted mtop10"><?php echo mb_substr(strip_tags($relatedArticle['description']),0,150); ?>...</div>
													</li>
													<hr class="hr-10" />
												<?php } ?>
											</ul>
										</div>
									<?php }	?>
									<?php hooks()->do_action('after_single_knowledge_base_article_customers_area',$article->articleid); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>



	<?php
