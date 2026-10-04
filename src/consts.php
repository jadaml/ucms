<?php
/*
 * This file is part of Micro Content Management System.
 * 
 * Micro Content Management System is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * 
 * Micro Content Management System is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License along with Micro Content Management System. If not, see <https://www.gnu.org/licenses/>. 
 */
/**
 * @file consts.php
 * @brief Constant values used by the µCMS system.
 * @author Ádám L. Juhász
 * @copyright GNU General Public License v3
 * @version 2.1
 * @date 2026
 */
const UCMS_VER_MAJOR='2';                                                       //!< Major version component of µCMS
const UCMS_VER_MINOR='1';                                                       //!< Minor version component of µCMS
const UCMS_VER_PATCH='1';                                                       //!< Patch version component of µCMS
const UCMS_VERSION= UCMS_VER_MAJOR . '.' . UCMS_VER_MINOR . '.' . UCMS_VER_PATCH; //!< Full version string of µCMS
const UCMS_COPY_YEARS='2025, 2026';                                             //!< Copyright year of µCMS
const UCMS_FALLBACK_ERROR_PAGE='err.md';                                        //!< Fall-back error page
const UCMS_FALLBACK_SITE_IMAGE='/images/ucms.png';                              //!< Fall-back site image
const UCMS_FALLBACK_URL_PATH_BASE='/index.php?';                                //!< Fall-back URL path base
const UCMS_FALLBACK_ORIGIN_SCHEME='http://';                                    //!< Fall-back origin scheme
const UCMS_MARKDOWN_EXTENSIONS=array('md', 'markdown');                         //!< Accepted markdown file extension list
const UCMS_SPECIAL_PAGE_PREFIX='*';                                             //!< Special page prefix
const UCMS_SPECIAL_PAGE_ABOUT='ABOUT';                                          //!< Special page 'about'
const UCMS_SPECIAL_PAGE_VERSION='VERSION';                                      //!< Special page 'version'
const UCMS_SPECIAL_PAGE_CONFIG='CONFIG';                                        //!< Special page 'config'
?>