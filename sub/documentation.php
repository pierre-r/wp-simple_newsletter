<div class="wrap">
    <h2><?php _e('How to use', 'wpsn'); ?></h2>
    <p><?php _e('You have two main possibilities to use : by widget or by direct call for theme integration', 'wpsn'); ?></p>
    <p><?php _e('For the first solution, simply go to <a href="widgets.php">Widgets page options</a> and set the differentions option for Simple Newsletter Wdiget.', 'wpsn'); ?></p>
    <h2><?php _e('Theme Integration', 'wpsn'); ?></h2>
    <p><?php _e('You can choose to integer the subscription form anyway by calling it directly. There are default options but it is best to set the differents options.', 'wpsn'); ?></p>
    <p><?php _e('Here are the default options and a call of the function.', 'wpsn'); ?></p>
    <pre style="background: #EEE; padding: 10px;">
&lt;?php
$form_atts = array(
            'title' => 'Newsletter',
            'show_on_front_page' => 1,
            'do_not_widget' => 0,
            'form_id' => 'simple_newsletter',
            'form_classes' => '',
            'submit_value' => __('Subscribe', 'wpsn'),
            'javascript_alert' => FALSE,
            'error_classes' => 'alert alert-error',
            'success_classes' => 'alert alert-success',
        );
echo wpsn_subscribe_form($form_atts);
?&gt;</pre>

    <h2><?php _e('TODO', 'wpsn'); ?></h2>
    <ul style="margin-left: 35px; list-style-type: disc;">
        <li><?php _e('Add attribute who check if form is called in widget or directly => a simple way to bypass the <code>show_on_front_page</code> option.', 'wpsn'); ?></li>
        <li><?php _e('Add a option to return only the subscription value (fail or success) to let the theme manage the display of error/success (a better Error Handling and less options)', 'wpsn'); ?></li>
        <li><?php _e('Add Bulk Actions to switch subscribers to unsubscribers and vice-versa + delete action', 'wpsn');  ?></li>
        <!--<li><?php //_e('', 'wpsn');  ?></li>-->
    </ul>
</div>
