<!--<div class="row footer-row">
  <div class="footer-logo col-md-4">
    <div class="image-wrapper">
      <img class="footer-img" src="https://www.northeastern.edu/clubswimming/wp-content/uploads/2016/05/GONU-e1462328416385.png">
    </div>
  </div>
  <div class="footer-logo col-md-4">
    <div class="image-wrapper">
      <img class="footer-img" src="https://www.northeastern.edu/clubswimming/wp-content/uploads/2016/05/Campus-Rec-Logo-e1462328484114.png" style="margin-top: 59px;">
    </div>
  </div>
  <div class="footer-logo col-md-4">
    <div class="image-wrapper">
      <img class="footer-img" src="https://www.northeastern.edu/clubswimming/wp-content/uploads/2016/05/ECCColor-e1462328462884.png">
    </div>
  </div>
</div>-->




<div class="modal fade in" id="SwimmerModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog swimmer" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title" id="myModalLabel">Liam Doyle</h4>
      </div>
      <div class="modal-body">
  	    <div class="modal-body-container">
	        <div class="row swimmer_modal_top">
    	      <div class="img_container col-md-3">
        	    <img src="https://www.northeastern.edu/clubswimming/wp-content/uploads/doyle_liam.jpg">
     	      </div>
      	    <div class="row info_container col-md-9">
          	  <div class="swimmer_page_info_row col-lg-12 stroke">
                <h3>Freestyle</h3>
          	  </div>
          	  <div class="swimmer_page_info_row col-lg-12 height">
                <h3>6' 1"</h3>
          	  </div>
          	  <div class="swimmer_page_info_row col-lg-12 year">
                <h3>Middler</h3>
          	  </div>
  			      <div class="swimmer_page_info_row col-lg-12 high_school">
                <h3>Hopkinton High School</h3>
          	  </div>
  			      <div class="swimmer_page_info_row col-lg-12 hometown">
                <h3>Hopkinton, MA</h3>
          	  </div>
      	    </div>
          </div>
          <div class="row swimmer_bio">
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam vel leo ultricies, commodo dolor vel, laoreet risus. Maecenas volutpat sapien non enim eleifend blandit. Aliquam erat volutpat. Cras pretium nisl at elit fermentum tincidunt. Aliquam scelerisque lorem eget sem fermentum egestas. Pellentesque fringilla, mauris vitae malesuada consequat, urna dui faucibus urna, id commodo dui mi sit amet massa. Quisque at nulla in ipsum pulvinar rutrum eget cursus quam. Vivamus vel leo facilisis, rhoncus arcu at, interdum turpis. Nullam ligula dolor, pellentesque nec varius a, posuere in sem. Integer metus ligula, efficitur ut dui id, sodales malesuada dolor. Vestibulum ut sapien tristique, condimentum massa id, posuere ligula. Vestibulum bibendum faucibus felis, sit amet aliquam ante finibus convallis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Aliquam erat volutpat. Pellentesque vitae lacus nisi. Phasellus in congue ex.</p>
          </div>
  	      <div class="row swimmer_modal_times">
      	    <div class="row key col-lg-12">
        	    <div class="col-md-3 col-xs-6">
            	  * (Relay Lead Off)
          	  </div>
          	  <div class="col-md-3 col-xs-6">
            	  # (Club Record)
          	  </div>
          	  <div class="col-md-3 col-xs-6">
            	  ^ (ECC Finalist)
          	  </div>
              <div class="col-md-3 col-xs-6">
            	  !! (ECC Record)
          	  </div>
      	    </div>
            <div class="col-lg-12" id="loading">
              <div id="load_icon_container">
                <img id="load_icon" src="https://upload.wikimedia.org/wikipedia/commons/b/b1/Loading_icon.gif">
              </div>
            </div>
            <div class="col-lg-12" id="tobeloaded">
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">

      </div>
    </div>
  </div>
</div>

<!-- Announcements Modal -->
<div class="modal fade" id="Announcements" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Title</h4>
      </div>
      <div class="modal-body">
        <div id="loading">
          <div id="load_icon_container">
            <img id="load_icon" src="https://upload.wikimedia.org/wikipedia/commons/b/b1/Loading_icon.gif">
          </div>
        </div>
        <div id="tobeloaded">
        </div>
      </div>
      <div class="modal-footer">
        <p class="date-author">Posted by Someone sometime</p>
      </div>
    </div>
  </div>
</div>
<?php wp_footer();?>
<script src="<?php bloginfo('url'); ?>/wp-content/themes/<?=get_stylesheet()?>/bootstrap/js/bootstrap.min.js"></script> <!-- Get Bootstrap JS -->
</body>
</html>
