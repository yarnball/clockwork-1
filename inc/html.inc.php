	<style type="text/css">
		.button {width: auto!important;}
		#validation {border-top: 1px solid #dfdfdf;padding: 10px 0}
	</style>
	<div class="wrap" id="clockwork-settings">
		<div id="icon-options-general" class="icon32"><br></div>
		<h2>Clockwork SMS for WordPress</h2>
		<div id="poststuff">
			<div id="post-body" class="metabox-holder columns-2">
				<div id="post-body-content">
					<div id="namediv" class="stuffbox">
						<h3 class="hndle"><?php _e('Clockwork SMS Settings','clockwork'); ?></h3>
						<div class="inside">
							<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
								<table class="form-table">

									<tr valign="top">
										<th scope="row">
											<label for="clockwork_api_key"><?php _e('API Key','clockwork'); ?></label>
										</th>
										<td>
											<input name="clockwork_api_key" type="text" id="clockwork_api_key" value="<?php echo esc_attr( get_option('clockwork_api_key')); ?>" class="regular-text code" />
											<p class="description"><?php _e('This is the API Key from your Clockwork Account.','clockwork'); ?></p>
										</td>
									</tr>

									<tr valign="top">
										<th scope="row">
											<label for="clockwork_default_message"><?php _e('The Default Message You Want to Send.','clockwork'); ?></label>
										</th>
										<td>
											<input name="clockwork_default_message" type="text" id="clockwork_default_message" value="<?php echo esc_attr( get_option('clockwork_default_message')); ?>" class="regular-text code" />
											<p class="description"><?php _e('This will be sent to the mobile phone that is specified in the form.','clockwork'); ?></p>
										</td>
									</tr>


								</table>

								<div id="validation">
									<?php wp_nonce_field('clockwork-settings');?>
									<input type="submit" value="<?php _e('Update','clockwork');?>" class="button-primary button" name="clockwork-settings"/>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
