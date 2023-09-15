<?php
get_header();
?>

<div class="container mt-4 locations-archive-body">
    <div class="filter-heading">
        <h3><?php custom_breadcrumbs(); ?></h3>
        <h3>Aangesloten locaties</h3>
    </div>
    <div class="row">
        <!-- Left Column: Search Filters -->
        <div class="col-md-4 ">
            <div class="card border border-1">
                <div class="card-body location-filter-top">
                    <h5 class="card-title">Search Filters</h5>
                    <!-- Add your search filter inputs and submit button here -->
                    <form>
                        <div class="form-group">
                        <div class="form-group">
                            <input type="text" class="form-control" id="location-name" name="filter1">
                        </div>

                        <div class="form-group">
                            <select class="form-control mt-3" id="radius-select">
                                <option value="10">+10km</option>
                                <option value="15">+15km</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success text-white mt-3">Submit</button>
                        </div>

                        </div>
                    </form>

                </div>

                <div class="card-body">

                    <div class="form-group mt-3">
                        <label for="radius-select">Minimaal Aantal personen</label>
                        <input type="text" class="form-control" id="location-name" name="filter1">
                    </div>
                    <div class="form-group mt-3">
                        <label for="radius-select">Aantal zalen</label>
                        <select class="form-control" id="radius-select">
                            <option value="1">1</option>
                            <option value="2">2</option>
                        </select>
                    </div>

                    <div class="form-group mt-3">
                    <label for="radius-select">Minimaal Aantal personen</label>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="option1" id="checkbox1">
                            <label class="form-check-label" for="checkbox1">
                                Option 1
                            </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="option2" id="checkbox2">
                            <label class="form-check-label" for="checkbox2">
                                Option 2
                            </label>
                        </div>
                    </div>


                </div>

            </div>
        </div>



        <!-- Right Column: Post List -->
        <div class="col-md-8">
            <div class="row">
                <?php
                // The WordPress Loop
                if (have_posts()) :
                    while (have_posts()) :
                        the_post();
                ?>

                        <div class="card mb-5">

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-7">

                                        <h2 class="card-title"><?php the_title(); ?></h2>
                                        <h5 class="font-weight-light"><?php the_field('alinea_1_titel'); ?></h5>



                                        <?php
                                        $desc_title_1 = get_field('alinea_1_titel');
                                        $desc_location_data = get_field('Locatiegegevens');

                                        if ($desc_title_1 && $desc_location_data) {
                                            $locatiegegevens_items = $desc_location_data['locatiegegevens_items'];
                                            if (!empty($locatiegegevens_items)) {
                                                echo '<ul class="list-unstyled mb-3" >';
                                                foreach ($locatiegegevens_items as $desc_location_item) {
                                                    echo '<li>';

                                                    // echo $desc_location_item['locatiegegevens_image'];
                                                    // Display the image on the left
                                                    if ($desc_location_item['locatiegegevens_image']) {
                                                        echo '<img src="' . $desc_location_item['locatiegegevens_image'] . '" alt="Location Image" class="location-image mr-3">';
                                                    }

                                                    // Display the other field value on the right
                                                    if ($desc_location_item['locatiegegevens_name']) {
                                                        echo '<span class="locatiegegevens_name mb-3">' . esc_html($desc_location_item['locatiegegevens_name']) . '</span>';
                                                    }

                                                    echo '</li>';
                                                }
                                                echo '</ul>';
                                            }
                                        }
                                        ?>






                                        <!-- You can add other post-related information here -->
                                        <a href="<?php the_permalink(); ?>" class="btn btn-primary">Bekijk locatie</a>
                                    </div>


                                    <div class="col-md-5">


                                        <?php
                                        // Display the post thumbnail (featured image)
                                        if (has_post_thumbnail()) {
                                            echo '<div class="card-img-top">';
                                            the_post_thumbnail('large', ['class' => 'img-fluid']);
                                            echo '</div>';
                                        }
                                        ?> </div>
                                </div>
                            </div>
                        </div>




                <?php
                    endwhile;
                else :
                    echo 'No posts found.';
                endif;
                ?>
            </div>
        </div>
    </div>
</div>

<?php
get_footer();
?>