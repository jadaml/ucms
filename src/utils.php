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
 * @file utils.php
 * Contains utility functions for Micro Content Management System.
 */

/**
 * This function checks if a file name already contains a valid markdown extension.
 * @param string $file The file name to check.
 * @return bool True if the file name has a valid markdown extension, false otherwise.
 */
function is_markdown_with_extension(string $file) {
    $ext = pathinfo($file, PATHINFO_EXTENSION);
    return $ext == 'md' || $ext == 'markdown';
}

/**
 * This function makes sure a file name have a valid markdown extension.
 * If the file name does not have a valid markdown extension, it will be appended.
 * @param string $file The file name to check.
 * @return string The file name with a valid markdown extension.
 */
function get_file_with_markdown_extension(string $file) {
    if (is_markdown_with_extension($file)) {
        return $file;
    }
    return $file . '.md';
}
?>