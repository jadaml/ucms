<?php
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