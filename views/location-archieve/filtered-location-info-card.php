<div class="card mb-5">
    <div class="card-body">
        <div class="row">
            <div class="col-md-7">
                <h2 class="card-title"><?php the_title(); ?></h2>
                <h5 class="font-weight-light"><?php the_field('alinea_1_titel'); ?></h5>
                <?php
                $locations_data = get_field('locations_data');
                ?>
                <div class="Locatiegegevens-item">
                    <p><i class="fas fa-location-pin"></i> <?php echo $locations_data['location_name_text']; ?></p>
                    <p><i class="fas fa-globe"></i> <?php echo  $locations_data['location_website_url']; ?></p>
                    <p><i class="fas fa-users"></i> <?php echo $locations_data['location_persons_number']; ?></p>
                    <p><i class="fas fa-building"></i> <?php echo $locations_data['location_rooms_number']; ?></p>
                </div>
                <!-- You can add other post-related information here -->
                <a href="<?php the_permalink(); ?>" class="btn btn-primary">Bekijk locatie</a>
            </div>
            <div class="col-md-5">
                <?php
                // Display the post thumbnail (featured image)
                if (has_post_thumbnail()) {
                    echo '<div class="card-img-top">';
                    the_post_thumbnail('large', ['class' => 'img-fluid location-feature-image']);
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </div>
</div>