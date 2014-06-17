<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<?php 
require("header.php");
?>
    <div id="myCarousel" class="carousel slide">
	
      <!-- Indicators -->
        <ol class="carousel-indicators">
          <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
          <li data-target="#myCarousel" data-slide-to="1"></li>
          <li data-target="#myCarousel" data-slide-to="2"></li>
        </ol>

        <!-- Wrapper for slides -->
        <div class="carousel-inner">
          <div class="item active">
            <div class="fill" style="background-image:url('img/slide/slide1.png');"></div>
            <div class="carousel-caption">
              <h2>Create New Document</h2>
              <a href="new_action.php" class="button">PROCEED</a>
            </div>
          </div>
          <div class="item">
            <div class="fill" style="background-image:url('img/slide/slide2.png');"></div>
            <div class="carousel-caption">
              <h2>Receive New Document</h2>
              <a href="main_documents.php" class="button">SEE MORE</a>
            </div>
          </div>
          <div class="item">
            <div class="fill" style="background-image:url('img/slide/slide3.png');"></div>
            <div class="carousel-caption">
              <h2>Search Archive</h2>
              <a href="search_archive.php" class="button">SEARCH</a>
            </div>
          </div>
        </div>

        <!-- Controls -->
        <a class="left carousel-control" href="#myCarousel" data-slide="prev">
          <i class="fa fa-angle-left"></i>
        </a>
        <a class="right carousel-control" href="#myCarousel" data-slide="next">
          <i class="fa fa-angle-right"></i>
        </a>
    </div>

    <div class="section">

      <div class="container">

        <div class="row">
          <div class="col-md-4 col-sm-4">
            <div class="block-icon">
              <i class="fa fa-rocket"></i>
            </div>

            <div class="block-body">
              <h2>Rapid Response</h2>
              <div class="line-subtitle"></div>
              <p>A system designed to meet immediate needs</p>
            </div>
          </div>
          <div class="col-md-4 col-sm-4">
            <div class="block-icon">
              <i class="fa fa-users"></i>
            </div>

            <div class="block-body">
              <h2>Digital Interaction</h2>
              <div class="line-subtitle"></div>
              <p>This projects aims to lead to a paperless system</p>
            </div>
          </div>
          <div class="col-md-4 col-sm-4">
            <div class="block-icon">
              <i class="fa fa-cog"></i>
            </div>

            <div class="block-body">
              <h2>Easy Interface</h2>
              <div class="line-subtitle"></div>
              <p>System optimized to make existing processes easier</p>
            </div>
          </div>
        </div><!-- /.row -->

      </div><!-- /.container -->

    </div><!-- /.section -->
</body>	
</html>