<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function getNav() {
    
    $output .= '    <nav class="navbar navbar-default navbar-inverse" role="navigation">
                        <div class="navbar-header">
                            <a class="navbar-brand" href="#">Capsula del Temps</a>
                        </div>
                        <div>
                            <!--Left Align-->
                            <ul class="nav navbar-nav navbar-left">
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        Equip 
                                        <b class="caret"></b>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a href="#">Alejandro</a></li>
                                        <li><a href="#">Marc</a></li>
                                        <li><a href="#">James</a></li>
                                        <li><a href="#">Sergio</a></li>
                                        <li><a href="#">Asif</a></li>
                                        <li><a href="#">Adrià</a></li>
                                        <li><a href="#">Vidal</a></li>
                                    </ul>
                                </li>
                            </ul>
                            
                        </div>
                    </nav>';
    
    return $output;
}