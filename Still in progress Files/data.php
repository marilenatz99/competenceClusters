<html>
<?php
include_once 'db.php';

$query2 = mysqli_query(
    $db,
    'SELECT *, c.id as cluster_id, scu.id as course_id, c.long_name AS cluster_long_name, c.short_name AS cluster_short_name, c.id AS cluster_id, scu.long_name AS course_long_name, scu.short_name AS course_short_name, c.description as cluster_description, scu.description as course_description 
    FROM `scu`, `cluster` as c 
    WHERE c.id = scu.clusterid
    ORDER BY scu.clusterid'
);

$courses = [];
if ($query2->num_rows > 0) {
    while ($row2 = mysqli_fetch_object($query2)) {
        $cluster_acronym = '';
        if ($row2->cluster_short_name != null) {
            $cluster_acronym = $row2->cluster_short_name;
        } else {
            $words = preg_split('/[\s,_-]+/', $row2->cluster_long_name);
            foreach ($words as $w) {
                $cluster_acronym .= mb_substr($w, 0, 1);
            }
        }

        $course_acronym = '';
        if ($row2->course_short_name != null) {
            $course_acronym = $row2->course_short_name;
        } else {
            $words = preg_split('/[\s,_-]+/', $row2->course_long_name);
            foreach ($words as $w) {
                $course_acronym .= mb_substr($w, 0, 1);
            }
        }

        $courses[$row2->course_id] = [
            'course_id' => $row2->course_id,
            'course_long_name' => $row2->course_long_name,
            'course_short_name' => $course_acronym,
            'course_description' => $row2->course_description,
            'course_modeid' => $row2->modeid,
            'course_granularityid' => $row2->granularityid,
            'course_pedagogical_approachid' => $row2->pedagogical_approachid,
            'course_ects' => $row2->ects,
            'course_total_work_hours' => $row2->total_work_hours,
            'course_auto_study_hours' => $row2->auto_study_hours,
            'course_sync_teaching_hours' => $row2->sync_teaching_hours,
            'course_async_teaching_hours' => $row2->async_teaching_hours,
            'course_theory_hours' => $row2->theory_hours,
            'course_practice_hours' => $row2->practice_hours,
            'course_work_based_hours' => $row2->work_based_hours,
            'course_interactive_activity_hours' =>
                $row2->interactive_activity_hours,
            'course_non_interactive_activity_hours' =>
                $row2->non_interactive_activity_hours,
            'course_degree' => $row2->degree,
            'parent_clusterid' => $row2->cluster_id,
            'size' => 2,
        ];
    }
} else {
    echo 'No courses were found...';
}

// echo json_encode($courses) . '<br/><br/>';

$query1 = mysqli_query(
    $db,
    'SELECT * FROM `cluster`ORDER BY parent_clusterid '
);

$clusters = [];
if ($query1->num_rows > 0) {
    while ($row = mysqli_fetch_object($query1)) {
        $acronym = '';
        if ($row->short_name != null) {
            $acronym = $row->short_name;
        } else {
            $words = preg_split('/[\s,_-]+/', $row->long_name);
            foreach ($words as $w) {
                $acronym .= mb_substr($w, 0, 1);
            }
        }

        $level = $row->parent_clusterid;
        if ($level == null) {
            $level = 0;
        }
        $clusters[$row->id] = [
            'id' => $row->id,
            'parent_id' => $level,
            'long_name' => $row->long_name,
            'short_name' => $acronym,
            'description' => $row->description,
            'size' => 1,
        ];
    }
} else {
    echo 'No clusters were found...';
}

// print_r($clusters);

$GLOBALS['lastEntry'] = null;
?>

<body>
    <div class="treemenu">
    <?php if (count($clusters) > 0) {
        generateTreeView($clusters, 0, 0, $courses);
    } ?>
    </div>

<?php
function generateTreeView(
    $clusters,
    $clusterCounter,
    $currentParent,
    $courses,
    $currLevel = 0,
    $prevLevel = -1
) {
    foreach ($clusters as $clusterId => $cluster) {
        if ($currentParent == $cluster['parent_id']) {
            if ($currLevel > $prevLevel) {
                echo " <ul class='tree'> ";
            }

            if ($currLevel == $prevLevel) {
                echo '</li>';
            }

            // not important
            $menuLevel = $cluster['parent_id'];
            echo '<br/><li> <label for="level' .
                $menuLevel .
                '" style="color: red;">[' .
                $cluster['short_name'] .
                '] ' .
                $cluster['long_name'] .
                '</label>';
            //

            echo '<br/>BEFORE LAST ENTRY======== ' .
                json_encode($GLOBALS['lastEntry']) .
                '<br/><br/>';

            # The "Competence Clusters" cluster (that contain all the subclusters ect.)
            if ($cluster['parent_id'] === 0) {
                $GLOBALS['obj']['id ' . $prevLevel + 1] = $cluster;
            }
            # The subclusters of "Competence Clusters" cluster
            else {
                $newCluster['id ' . $prevLevel + 1] = $cluster;

                $counter = 0;
                # Find the courses that is inside of the cluster
                foreach ($courses as $courseId => $course) {
                    if ($course['parent_clusterid'] === $cluster['id']) {
                        $newCourse['id ' . $counter++] = $course;
                        $newCluster['id ' . $prevLevel + 1][
                            'children'
                        ] = $newCourse;
                    }
                }

                # First Subcluster
                if ($currLevel > $prevLevel) {
                    $GLOBALS['lastEntry']['id ' . $clusterCounter]
                        ? ($GLOBALS['lastEntry']['id ' . $clusterCounter][
                            'children'
                        ] = $newCluster)
                        : ($GLOBALS['lastEntry'] = $newCluster);

                    echo '<br/>CHILD AFTER<br/>' .
                        json_encode($GLOBALS['lastEntry']) .
                        '<br/>' .
                        json_encode($GLOBALS['obj']);
                    $clusterCounter++;
                }
                # All the other subclusters
                elseif ($currLevel === $prevLevel) {
                    $clusterCounter++;

                    echo '<br/>SECOND, THIRD, .... SUBCLUSTERS<br/>' .
                        json_encode($GLOBALS['lastEntry']) .
                        '<br/>' .
                        json_encode($GLOBALS['obj']);

                    $GLOBALS['lastEntry']['id ' . $clusterCounter] =
                        $newCluster['id ' . $prevLevel + 1];
                }

                $GLOBALS['obj']['id 0']['children'] = $GLOBALS['lastEntry'];
            }
            echo '<br/>_____________<br/>ALL: <br/>' .
                json_encode($GLOBALS['obj']) .
                '<br/>---------------lastEntry: <br/>' .
                json_encode($GLOBALS['lastEntry']);

            if ($currLevel > $prevLevel) {
                $prevLevel = $currLevel;
            }

            $currLevel++;

            generateTreeView(
                $clusters,
                $clusterCounter,
                $clusterId,
                $courses,
                $currLevel,
                $prevLevel
            );
            $currLevel--;
        }
    }

    if ($currLevel == $prevLevel) {
        echo ' </li></ul>';
    }
}

echo '<br/><br/>';

$object = json_encode($GLOBALS['obj']);

echo $object;

// mysqli_close($db);
?>
</body>
</html>
