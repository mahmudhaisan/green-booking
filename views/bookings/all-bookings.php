<div class="container my-3">
    <div class="row">
        <div class="col-md-2"></div>

        <div class="col-md-8">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="mb-4">Bekijk alle boekingen</h1>
                    <div class="bg-light p-3 rounded shadow">
                        <table class="table table-bordered table-striped">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th scope="col" class="th-custom">Datum</th>
                                    <th scope="col" class="th-custom">Boeker</th>
                                    <th scope="col" class="th-custom">GESPAARDE BOMEN</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            global $wpdb;
                            $table_name = $wpdb->prefix . 'booking_options';

                            // Get the current user's username or identifier, depending on how it's stored
                            $current_user_id = get_current_user_id(); // Replace with your method to get the current user's name

                            // Query to retrieve the latest 10 records for the current user ordered by event date
                            $query = "SELECT * FROM $table_name WHERE created_by= $current_user_id ORDER BY created_time DESC";

                            $results = $wpdb->get_results($query);

        
                            // Check if there are results
                            if ($results) {
                                foreach ($results as $result) {
                                    echo '<tr>';
                                    echo '<td>' . date('Y-m-d', strtotime($result->created_time)) . '</td>';
                                    echo '<td>' . $result->created_for . '</td>';
                                    echo '<td>' . $result->total_trees . '</td>';
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="3">Er zijn geen records gevonden voor de huidige gebruiker.</td></tr>';
                            }

                            ?>

                        </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-2"></div>
    </div>
</div>