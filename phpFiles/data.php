<?php
    // Connect database
    include_once 'db.php';

    // Get courses from database
    $query2 = mysqli_query( $db,
        'SELECT *, 
                c.id AS cluster_id, 
                c.long_name AS cluster_long_name, 
                c.short_name AS cluster_short_name,
                c.description AS cluster_description, 
                scu.id AS course_id, 
                scu.long_name AS course_long_name, 
                scu.short_name AS course_short_name, 
                scu.description AS course_description, 
                mode.name AS course_mode,
                g.granularity AS course_granularity,
                p.name AS course_pedagogical_approachid
        FROM `scu`
        INNER JOIN `cluster` as c
            ON c.id = scu.clusterid
        INNER JOIN `mode`
            ON mode.id = scu.modeid
        INNER JOIN `granularity` as g
            ON g.id = scu.granularityid
        INNER JOIN `pedagogical_approach` as p
            ON p.id = scu.pedagogical_approachid
        ORDER BY scu.clusterid'
    );

    $courses = [];
    if ($query2->num_rows > 0) {
        while ($row2 = mysqli_fetch_object($query2)) {

            // If there isn't a short_name, short_name = the first letters of the long_name
            if (!$row2->course_short_name != null) {
                $words = preg_split('/[\s,_-]+/', $row2->course_long_name);
                foreach ($words as $w) {
                    $row2->course_short_name .= mb_substr($w, 0, 1);
                }
            }

            // Insert every course into the array with the specific fields
            $courses[$row2->course_id] = [
                'id' => $row2->course_id,
                'long_name' => $row2->course_long_name,
                'short_name' => $row2->course_short_name,
                'description' => $row2->course_description,
                'mode' => $row2->course_mode,
                'granularity' => $row2->course_granularity,
                'pedagogical_approach' => $row2->course_pedagogical_approachid,
                'ects' => $row2->ects,
                'total_work_hours' => $row2->total_work_hours,
                'auto_study_hours' => $row2->auto_study_hours,
                'sync_teaching_hours' => $row2->sync_teaching_hours,
                'async_teaching_hours' => $row2->async_teaching_hours,
                'theory_hours' => $row2->theory_hours,
                'practice_hours' => $row2->practice_hours,
                'work_based_hours' => $row2->work_based_hours,
                'interactive_activity_hours' =>
                    $row2->interactive_activity_hours,
                'non_interactive_activity_hours' =>
                    $row2->non_interactive_activity_hours,
                'degree' => $row2->degree,
                'parent_clusterid' => $row2->cluster_id,
                'size' => 2,
            ];
        }
    } else {
        echo 'No courses were found...';
    }

    // Get clusters from database
    $query1 = mysqli_query($db, 'SELECT * FROM `cluster`ORDER BY parent_clusterid ');

    $clusters = [];
    if ($query1->num_rows > 0) {
        while ($row = mysqli_fetch_object($query1)) {

            // If there isn't a short_name, short_name = the first letters of the long_name
            if (!$row->short_name) {
                $words = preg_split('/[\s,_-]+/', $row->long_name);
                foreach ($words as $w) {
                    $row->short_name .= mb_substr($w, 0, 1);
                }
            }

            // Insert every cluster into the array with the specific fields, and if there is no parent_clusterid (that means its the root), parent_clusterid = 0
            $clusters[$row->id] = [
                'id' => $row->id,
                'parent_id' => ($row->parent_clusterid) ? $row->parent_clusterid : 0,
                'long_name' => $row->long_name,
                'short_name' => $row->short_name,
                'description' => $row->description,
                'size' => 1,
                'children' => []
            ];
        }
    } else {
        echo 'No clusters were found...';
    }


    // If there are clusters, call the function
    if (count($clusters) > 0) {
        generateTreeView($clusters, 0, $courses);
    }
    else
        echo 'There are no clusters';


    function generateTreeView( $clusters, $currentParent, $courses, $currLevel = 0, $prevLevel = -1 ) {

        foreach ($clusters as $clusterId => $cluster) {
            if ($currentParent == $cluster['parent_id']) {

                # The "Competence Clusters" cluster (that contain all the subclusters ect.) - the root
                if ($cluster['parent_id'] === 0) {
                    $GLOBALS['obj'] = $cluster;
                }
                # The subclusters of "Competence Clusters" cluster
                else {

                    # Find the courses that are inside of the cluster
                    foreach ($courses as $courseId => $course) {
                        if ($course['parent_clusterid'] === $cluster['id']) {
                            array_push($cluster['children'], $course);
                        }
                    }

                    # First Subcluster
                    if ($currLevel > $prevLevel) {

                        if($GLOBALS['obj']['id'] === $cluster['parent_id']){
                            // echo '<BR>FOR - YES, CC parent ';
                            array_push($GLOBALS['obj']['children'], $cluster);
                        }
                        else {
                            // echo '<BR>1 - FOR - PAIDI ' . json_encode($GLOBALS['obj']['children']);

                            foreach ($GLOBALS['obj']['children'] as $itemClone1 => $item) {

                                // echo '<BR><br>FOR: ' . $item['long_name'] .
                                //  ', '. $item['children'].
                                //   ', ' . $cluster['parent_id'];
                                
                                if ($item['id'] === $cluster['parent_id']) {

                                    // echo '<BR><br>FOR - YES ' . $itemClone1;
                                    array_push($GLOBALS['obj']['children'][$itemClone1]['children'], $cluster);
                                    // echo '<BR>173: ' . json_encode($GLOBALS['obj']['children'][$itemClone1]);
                                }
                                else {
                                    // echo '<BR>OXI PAIDI';
                                    
                                    
                                //     foreach ($item as $itemId => $parent) {
                                //         $parentS = $parent['children'][$itemId];
                                //         if ($parentS['id'] === $cluster['parent_id']) {
                                //             // echo '<BR><br>FOR - NO ' . $itemClone1;
                                //         }
                                //     }
                                                    
                                }
                            }
                        }

                    }
                    # All the other subclusters
                    elseif ($currLevel === $prevLevel) {
                    
                        if($GLOBALS['obj']['id'] === $cluster['parent_id']){
                            // echo '<BR>FOR - YES, CC parent ';
                            array_push($GLOBALS['obj']['children'], $cluster);
                        }
                        else {
                            // echo '<BR>2 - FOR - PAIDI ' . json_encode($GLOBALS['obj']['children']);

                            foreach ($GLOBALS['obj']['children'] as $itemClone1 => $item) {

                                // echo '<BR><br>FOR: ' . $item['long_name'] .
                                //  ', '. $item['children'].
                                //   ', ' . $cluster['parent_id'];
                                
                                if ($item['id'] === $cluster['parent_id']) {

                                    // echo '<BR><br>FOR - YES ' . $itemClone1;
                                    array_push($GLOBALS['obj']['children'][$itemClone1]['children'], $cluster);
                                    // echo '<BR>208: ' . json_encode($GLOBALS['obj']['children'][$itemClone1]);
                                }
                                else {
                                    // echo '<BR>GRANDCHILD';
                                    
                                //     foreach ($item as $itemId => $parent) {
                                //         $parentS = $parent['children'][$itemId];
                                //         if ($parentS['id'] === $cluster['parent_id']) {
                                //             // echo '<BR><br>FOR - NO ' . $itemClone1;
                                //         }
                                //     }
                                                    
                                }
                            }
                        }
                    }                          
                }


                if ($currLevel > $prevLevel) {
                    $prevLevel = $currLevel;
                }

                $currLevel++;

                generateTreeView( $clusters, $clusterId, $courses, $currLevel, $prevLevel );

                $currLevel--;
            }
        }
    }

    $object = json_encode($GLOBALS['obj']);

?>