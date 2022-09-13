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

$data = [];
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

        $data[$row2->course_id] = [
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
            // 'cluster_id' => $row2->cluster_id,
            // 'cluster_long_name' => $row2->cluster_long_name,
            // 'cluster_short_name' => $cluster_acronym,
            // 'cluster_description' => $row2->cluster_description,
            'parent_clusterid' => $row2->clusterid,
        ];
    }
} else {
    echo 'No courses were found...';
}

// echo json_encode($data) . '<br/><br/>';

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
        ];
    }
} else {
    echo 'No clusters were found...';
}

// print_r($clusters);
?>
 
 <head>
    <style>
        input{ font-size: 1em; }
        ol.tree
        {
            padding: 0 0 0 20px;
            width: 320px;
        }
        li 
        {
            position: relative; 
            margin-left: -5px;
            list-style: none;
        }
        li input
        {
            position: absolute;
            left: 0;
            margin-left: 0;
            opacity: 0;
            z-index: 2;
            cursor: pointer;
            height: 1em;
            width: 1em;
            top: 0;
        }
        li input + ol
        {
            background: url(img/arrow-down.png) 45px -3px no-repeat;
            margin: -0.938em 0 0 -44px;
            height: 1em;
        }
        li input + ol > li { display: none; margin-left: -14px !important; padding-left: 1px; }
        li label
        {
            cursor: pointer;
            display: block;
            padding-left: 24px;
        }
        
        li input:checked + ol
        {
            background: url(img/arrow-up.png) 45px 4px no-repeat;
            margin: -1.25em 0 0 -44px;
            padding: 1.563em 0 0 80px;
            height: auto;
        }
        li input:checked + ol > li { display: block; margin: 0 0 0.125em;}
        li input:checked + ol > li:last-child { margin: 0 0 0.063em; }
    </style>
 </head>

<body>
    <div class="treemenu">
    <?php if (count($clusters) > 0) {
        include_once 'db.php';
        $all = [];
        $lastEntry = $parent = null;
        generateTreeView($clusters, $all, $lastEntry, $parent, $db, 0);
    } ?>
    </div>

<?php
function generateTreeView(
    $clusters,
    $all,
    $lastEntry,
    $parent,
    $db,
    $currentParent,
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

            $menuLevel = $cluster['parent_id'];
            echo '<br/><li> <label for="level' .
                $menuLevel .
                '" style="color: red;">[' .
                $cluster['short_name'] .
                '] ' .
                $cluster['long_name'] .
                '</label>';

            if ($menuLevel == 0) {
                $all['id ' . $prevLevel + 1] = [
                    'id' => $cluster['id'],
                    'parent_id' => $cluster['parent_id'],
                    'long_name' => $cluster['long_name'],
                    'short_name' => $cluster['short_name'],
                    'description' => $cluster['description'],
                    'children' => [],
                ];
                $lastEntry = $parent = $all;
            } else {
                // $parent = $all['id ' . $prevLevel]['children'];
                $new['id ' . $prevLevel + 1] = [
                    'id' => $cluster['id'],
                    'parent_id' => $cluster['parent_id'],
                    'long_name' => $cluster['long_name'],
                    'short_name' => $cluster['short_name'],
                    'description' => $cluster['description'],
                    'children' => [],
                ];
                if ($currLevel > $prevLevel) {
                    $parent = $parent['id ' . $prevLevel]['children'];

                    echo '<br/>PAIDI<br/>' . json_encode($lastEntry);

                    // $lastEntry = $lastEntry['id ' . $prevLevel]['children'];

                    echo '<br/>PAIDI TOU PAIDIOU<br/>' .
                        json_encode($lastEntry);

                    $lastEntry['id ' . $prevLevel]['children'] = $new;
                    // $lastEntry = $new;

                    echo '<br/>PAIDI META<br/>' . json_encode($lastEntry);

                    $lastEntry = $lastEntry['id ' . $prevLevel]['children'];

                    $all['id ' . $prevLevel]['children'] = $parent;

                    echo '<br/>_____________<br/>';
                } elseif ($currLevel == $prevLevel) {
                    // $lastEntry = $lastEntry['id ' . $prevLevel - 1]['children'];
                    echo '<br/>TA PAIDIA<br/>' . json_encode($lastEntry);
                }

                echo '<br/>_____________<br/>ALL: <br/>' .
                    json_encode($all) .
                    '<br/>---------------LASTENTRY: <br/>' .
                    json_encode($lastEntry) .
                    '<br/>---------------PARENT: <br/>' .
                    json_encode($parent);

                // if ($currLevel > $prevLevel) {
                //     echo '<br/>PAIDI META<br/>' . json_encode($lastEntry);
                //     $lastEntry = $lastEntry['id ' . $prevLevel]['children'];
                // }
            }

            // echo '<br/><br/>---------------------------------<br/>$all:<br/>';
            // print_r($all);
            // echo '<br/>_____________<br/>' . json_encode($all);
            // echo '<br/>~~~~~~~~~~~~~~~~~~~<br/>$lastEntry:<br/>';
            // print_r($lastEntry);
            // echo '<br/>_____________<br/>' . json_encode($lastEntry);
            echo '<br/>---------------PARENT: <br/>' . json_encode($parent);

            if ($currLevel > $prevLevel) {
                $prevLevel = $currLevel;
            }

            $currLevel++;

            generateTreeView(
                $clusters,
                $all,
                $lastEntry,
                $parent,
                $db,
                $clusterId,
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

$object = json_encode($clusters);

// foreach ($object as $obj) {
//     print_r($obj);
// }

// mysqli_close($db);
?>
</body>
</html>
