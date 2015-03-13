<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function getNav() {
    $output .= '<nav class="navbar navbar-default" role="navigation">
   <div class="navbar-header">
      <a class="navbar-brand" href="#">TutorialsPoint</a>
   </div>
   <div>
      <!--Left Align-->
      <ul class="nav navbar-nav navbar-left">
         <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
               Java 
               <b class="caret"></b>
            </a>
            <ul class="dropdown-menu">
               <li><a href="#">jmeter</a></li>
               <li><a href="#">EJB</a></li>
               <li><a href="#">Jasper Report</a></li>
               <li class="divider"></li>
               <li><a href="#">Separated link</a></li>
               <li class="divider"></li>
               <li><a href="#">One more separated link</a></li>
            </ul>
         </li>
      </ul>
      <form class="navbar-form navbar-left" role="search">
         <button type="submit" class="btn btn-default">
            Left align-Submit Button   
         </button>
      </form> 
      <p class="navbar-text navbar-left">Left align-Text</p>
      <!--Right Align-->
      <ul class="nav navbar-nav navbar-right">
         <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
               Java <b class="caret"></b>
            </a>
            <ul class="dropdown-menu">
               <li><a href="#">jmeter</a></li>
               <li><a href="#">EJB</a></li>
               <li><a href="#">Jasper Report</a></li>
               <li class="divider"></li>
               <li><a href="#">Separated link</a></li>
               <li class="divider"></li>
               <li><a href="#">One more separated link</a></li>
            </ul>
         </li>
      </ul>
      <form class="navbar-form navbar-right" role="search">
         <button type="submit" class="btn btn-default">
            Right align-Submit Button
         </button>
      </form> 
      <p class="navbar-text navbar-right">Right align-Text</p>
   </div>
</nav>';
    return $output;
}
