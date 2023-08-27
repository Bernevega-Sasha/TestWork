    <?php
    /*
        Template name: CREATE PRODUCT
    */
    get_header();?>
    <main id="main" class="site-main">
    <h1>CREATE PRODUCT</h1>

    <form action="" method="POST" enctype="multipart/form-data">
        <label for="productName">Product name:</label>
        <input type="text" id="productName" name="productName" required><br><br>

        <label for="productPrice">Product Price:</label>
        <input type="number" id="productPrice" name="productPrice" required><br><br>

        <label for="productType">Product type:</label>
        <select id="productType" name="productType">
            <option value="rare">rare</option>
            <option value="frequent">frequent</option>
            <option value="unusual">unusual</option>
        </select><br><br>

        <label for="userFile">Product picture:</label>
        <input type='file' name='userFile'><br><br>
        <label for="creationDate">Product creation date:</label>
        <input type="date" id="creationDate" name="creationDate" required>

        <input type="submit" name='create' value="Create">
    </form>
    </main>

    <?
    if (isset($_POST['create'])) {
        echo "Товар создан";
        $post_title = $_POST["productName"];
        $price = $_POST["productPrice"];
        $custom_type = $_POST["productType"];
        $custom_date = $_POST["creationDate"];
        $target_File = WP_CONTENT_DIR . '/uploads/' . basename($_FILES['userFile']['name']);
        $custom_image = '/wp-content/uploads/' . basename($_FILES['userFile']['name']);
        move_uploaded_file($_FILES['userFile']['tmp_name'], $target_File); 
    
        $new_product = array(
            'post_title' => $post_title,
            'post_status'=> 'publish',
            'post_type'  => 'product',
            'meta_input' => [ 
                '_price'  => $price,
                'custom_type'     => $custom_type,
                'custom_date'     => $custom_date,
                'custom_image'	  => $custom_image,
    
                ],
        );
        $new_post_id = wp_insert_post($new_product);

        $attachment_data = array(
            'post_title'     => basename($_FILES['userFile']['name']),
            'post_status'    => 'inherit',
            'post_mime_type' => wp_check_filetype(basename($_FILES['userFile']['name']))['type'],
        );

        $attachment_id = wp_insert_attachment($attachment_data, $custom_image, $post_id);
        set_post_thumbnail($new_post_id, $attachment_id);


        

}
    get_footer();