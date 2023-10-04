<?php
get_header();
?>

<div class="container mt-4 locations-archive-body">
    <div class="filter-heading">
        <h3><?php custom_breadcrumbs(); ?></h3>
        <h3>Aangesloten locaties</h3>
    </div>
    <div class="row mb-5">

        <?php  include GRBP_PLUGINS_PATH . '/views/location-archieve/left-filter-bar.php'; ?>
        <?php  include GRBP_PLUGINS_PATH . '/views/location-archieve/right-card-contents.php'; ?>

    </div>
</div>

<?php
get_footer();

// Define the calculateDistance function here
