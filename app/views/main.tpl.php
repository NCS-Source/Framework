<?php
/**
 * MIT License
 * ===========
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * -----------------------------------------------------------------------
 *
 * @author Martin "Garth" Zander <garth@new-crusader.de>
 * @license MIT License https://github.com/NCS-Source/Framework/blob/master/LICENSE
 * @package NCS Framework
 * 
 * @global string $tpl_var_title
 * @global string $tpl_var_execute_time
 * @global string $content
 */

?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $tpl_var_title; ?></title>
        <meta name="robots" content="all">
        <meta name="language" content="de">
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" type="text/css" href="/css/main.css?1" />
        <script type="text/javascript" src="/css/main.js?1"></script>
    </head>
    <body>
        <h1><?php echo $tpl_var_title; ?></h1>
        <div>
<?php echo $content; ?>
            <div class="footer">
                <div><?php echo $tpl_var_execute_time; ?> sec</div>
            </div>
        </div>
    </body>
</html>