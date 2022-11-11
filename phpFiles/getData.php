<script>
    const svgContainer = document.querySelector("#svgContainer");
    const superclustDiv = document.querySelector(".superclusters");
    const subclustDiv = document.querySelector(".subclusters");
    const titleP = document.querySelector("#title");
    const descrP = document.querySelector(".desc p");
    const superclustP = document.querySelector(".superclusters ul");
    const subclustP = document.querySelector(".subclusters ul");
    const skillsDiv = document.querySelector("#skills");
    const full_names = document.querySelector("#full");
    const short_names = document.querySelector("#short");
    const applyToCourse = document.getElementById("ApplyToCourse");
    const applyFullSpec = document.getElementById("ApplyFullSpec");
    const ul = document.querySelector("#searchItems");
    const searchChildren = document.querySelector("#searchChildren");
    var bool = false;


    // Get data from database
    <?php include 'data.php';?>
    var fullObj = <?php echo $object; ?>;


    // Create bubbles
    chart(fullObj);

    var node, circle;

    function chart(data) {

        var root = data;

        let multicolor = true;
        let hexcolor = "#fff";
        // let hsl = function (d) {
        //     for (var i = 0, j = -1, c; i < width; ++i) {
        //         c = d3.rgb(color.call(this, d.color(i / width)));
        //         image.data[++j] = c.r;
        //         image.data[++j] = c.g;
        //         image.data[++j] = c.b;
        //         image.data[++j] = 255;
        //     }
        // };
        // var rainbow = ["#6e40aa", "#7140ab", "#743fac", "#773fad", "#7a3fae", "#7d3faf", "#803eb0", "#833eb0", "#873eb1", "#8a3eb2", "#8d3eb2", "#903db2", "#943db3", "#973db3", "#9a3db3", "#9d3db3", "#a13db3", "#a43db3", "#a73cb3", "#aa3cb2", "#ae3cb2", "#b13cb2", "#b43cb1", "#b73cb0", "#ba3cb0", "#be3caf", "#c13dae", "#c43dad", "#c73dac", "#ca3dab", "#cd3daa", "#d03ea9", "#d33ea7", "#d53ea6", "#d83fa4", "#db3fa3", "#de3fa1", "#e040a0", "#e3409e", "#e5419c", "#e8429a", "#ea4298", "#ed4396", "#ef4494", "#f14592", "#f34590", "#f5468e", "#f7478c", "#f9488a", "#fb4987", "#fd4a85", "#fe4b83", "#ff4d80", "#ff4e7e", "#ff4f7b", "#ff5079", "#ff5276", "#ff5374", "#ff5572", "#ff566f", "#ff586d", "#ff596a", "#ff5b68", "#ff5d65", "#ff5e63", "#ff6060", "#ff625e", "#ff645b", "#ff6659", "#ff6857", "#ff6a54", "#ff6c52", "#ff6e50", "#ff704e", "#ff724c", "#ff744a", "#ff7648", "#ff7946", "#ff7b44", "#ff7d42", "#ff8040", "#ff823e", "#ff843d", "#ff873b", "#ff893a", "#ff8c38", "#ff8e37", "#fe9136", "#fd9334", "#fb9633", "#f99832", "#f89b32", "#f69d31", "#f4a030", "#f2a32f", "#f0a52f", "#eea82f", "#ecaa2e", "#eaad2e", "#e8b02e", "#e6b22e", "#e4b52e", "#e2b72f", "#e0ba2f", "#debc30", "#dbbf30", "#d9c131", "#d7c432", "#d5c633", "#d3c934", "#d1cb35", "#cece36", "#ccd038", "#cad239", "#c8d53b", "#c6d73c", "#c4d93e", "#c2db40", "#c0dd42", "#bee044", "#bce247", "#bae449", "#b8e64b", "#b6e84e", "#b5ea51", "#b3eb53", "#b1ed56", "#b0ef59", "#adf05a", "#aaf159", "#a6f159", "#a2f258", "#9ef258", "#9af357", "#96f357", "#93f457", "#8ff457", "#8bf457", "#87f557", "#83f557", "#80f558", "#7cf658", "#78f659", "#74f65a", "#71f65b", "#6df65c", "#6af75d", "#66f75e", "#63f75f", "#5ff761", "#5cf662", "#59f664", "#55f665", "#52f667", "#4ff669", "#4cf56a", "#49f56c", "#46f46e", "#43f470", "#41f373", "#3ef375", "#3bf277", "#39f279", "#37f17c", "#34f07e", "#32ef80", "#30ee83", "#2eed85", "#2cec88", "#2aeb8a", "#28ea8d", "#27e98f", "#25e892", "#24e795", "#22e597", "#21e49a", "#20e29d", "#1fe19f", "#1edfa2", "#1ddea4", "#1cdca7", "#1bdbaa", "#1bd9ac", "#1ad7af", "#1ad5b1", "#1ad4b4", "#19d2b6", "#19d0b8", "#19cebb", "#19ccbd", "#19cabf", "#1ac8c1", "#1ac6c4", "#1ac4c6", "#1bc2c8", "#1bbfca", "#1cbdcc", "#1dbbcd", "#1db9cf", "#1eb6d1", "#1fb4d2", "#20b2d4", "#21afd5", "#22add7", "#23abd8", "#25a8d9", "#26a6db", "#27a4dc", "#29a1dd", "#2a9fdd", "#2b9cde", "#2d9adf", "#2e98e0", "#3095e0", "#3293e1", "#3390e1", "#358ee1", "#378ce1", "#3889e1", "#3a87e1", "#3c84e1", "#3d82e1", "#3f80e1", "#417de0", "#437be0", "#4479df", "#4676df", "#4874de", "#4a72dd", "#4b70dc", "#4d6ddb", "#4f6bda", "#5169d9", "#5267d7", "#5465d6", "#5663d5", "#5761d3", "#595fd1", "#5a5dd0", "#5c5bce", "#5d59cc", "#5f57ca", "#6055c8", "#6153c6", "#6351c4", "#6450c2", "#654ec0", "#664cbe", "#674abb", "#6849b9", "#6a47b7", "#6a46b4", "#6b44b2", "#6c43af", "#6d41ad", "#6e40aa"];


        // Setting information on landing
        superclustP.innerHTML = '';
        subclustP.innerHTML = '';

        // Display root cluster title
        titleP.innerHTML = '[' + root.short_name + '] ' + root.long_name;
        descrP.innerHTML = root.description;
        
        // close searchChildren
        var li = document.createElement('li');
        var link = document.createElement('a');
        link.href = '#';
        var img = document.createElement('img');
        img.src = '../assets/icons/close.png';
        link.innerHTML = 'Cancel ';
        link.appendChild(img);
        link.addEventListener("click", () => {
            searchChildren.style.display = 'none';
        });
        link.className = 'elementor-sub-item';
        li.className = "menu-item menu-item-type-post_type menu-item-object-page menu-item-6800 font-weight-bold text-right";
        link.style = "text-decoration: none;";
        li.appendChild(link);
        ul.appendChild(li);


        // Display subclusters & searchChildren
        for (const key in root.children) {
            const element = root.children[key];
            var li1 = lists(element);
            var li2 = lists(element);
            subclustP.appendChild(li1);
            ul.appendChild(li2); 

        }


        // Setting colors for the bubbles
        var t = 0;
        function setColorScheme(multi) {
            if (multi) {
                t += 0.02;
                let color = //d3.interpolateRainbow(t)
                    d3.scaleOrdinal().range(d3.schemePastel1)
                return color;
            }
        }

        let color = setColorScheme(multicolor);

        function setCircleColor(obj) {
            let depth = obj.depth;
            while (obj.depth > 1) {
                obj = obj.parent;
            }

            // console.log(obj, depth, obj.data.name)
            let newcolor = multicolor ? d3.hsl(color(obj.data.long_name)) : d3.hsl(hexcolor);
            newcolor.l -= depth == 1 ? 0 : depth * 0.1;

            if (depth == 0) newcolor = '#999';
            return newcolor;
        }

        // Setting stroke to bubbles
        function setStrokeColor(obj) {
            let depth = obj.depth;
            while (obj.depth > 1) {
                obj = obj.parent;
            }
            let strokecolor = multicolor ? d3.hsl(color(obj.data.long_name)) : d3.hsl(hexcolor);
            return strokecolor;
        }

        // Creating svg
        var svg = d3.select("svg")
            .attr("width", '600')
            .attr("height", '600');

        svg = d3.select("svg"),
            margin = 20,
            diameter = +svg.attr("width"),
            g = svg
                .append("g")
                .attr("transform", "translate(" + diameter / 2 + "," + diameter / 2 + ")");

        var pack = d3
            .pack()
            .size([diameter - margin, diameter - margin])
            .padding(2);

        root = d3
            .hierarchy(root)
            .sum(function (d) {
                return d.size;
            })
            .sort(function (a, b) {
                return b.value - a.value;
            });

        var focus = root,
            nodes = pack(root).descendants(),
            view;

        fullObj = nodes;

        circle = g
            .selectAll("circle")
            .data(nodes)
            .enter()
            .append("circle")
            .attr("class", function (d) {
                return d.parent
                    ? d.children
                        ? "node"
                        : "node node--leaf"
                    : "node node--root";
            })
            .style("fill", setCircleColor)
            .attr("stroke", setStrokeColor)
            .on("mouseover", function () { d3.select(this).attr("stroke", d => d.depth == 1 ? "black" : "white"); })
            .on("mouseout", function () { d3.select(this).attr("stroke", setStrokeColor); })
            .on("click", function (d, data) {
                displayInfo(data);
                if (focus !== d) zoom(data);
            })

        var text = g
            .selectAll("text")
            .data(nodes)
            .enter()
            .append("text")
            .attr("class", "label")
            .style("fill-opacity", function (d) {
                return d.parent === root ? 1 : 0;
            })
            .style("display", function (d) {
                return d.parent === root ? "inline" : "none";
            })
            .text(function (d) {
                var Name = '';
                !bool ?
                    Name = d.data.short_name
                    :
                    Name = d.data.long_name;
                // console.log(bool, Name)
                return Name;
            });

        node = g.selectAll("circle,text");

        zoomTo([root.x, root.y, root.r * 2 + margin]);
    }

    function zoom(d) {
        focus = d;
        var avail = 'site-button';
        var dis = 'site-button isDisabled';

        var transition = d3.transition()
            .duration(3000)
            .tween("zoom", function (d) {
                var i = d3.interpolateZoom(view, [
                    focus.x,
                    focus.y,
                    focus.r * 2 + margin
                ]);
                return function (t) {
                    zoomTo(i(t));
                };
            });

        transition
            .selectAll("text")
            .style("fill-opacity", function (d) {
                return d.parent === focus ? 1 : focus.height == 0 ? 1 : 0;
            })
            .style("font-size", function (d) {
                return d.parent === focus ? "14px" : focus.height == 0 ? "16px" : "12px";
            })
            .on("start", function (d) {
                if (d.parent === focus) {
                    this.style.display = "inline";
                    displayButtons();
                }
                else if (d === focus && focus.height === 0) {
                    this.style.display = "inline";
                    applyToCourse.className = avail;
                    applyFullSpec.className = avail;
                    applyToCourse.href = './redirect.php?courseId=' + focus.data.id;
                }
            })
            .on("end", function (d) {
                if (d === focus) {
                    this.style.display = "inline";
                    displayButtons();
                }
                else {
                    this.style.display = "none";
                    displayButtons();
                }

                if (d.parent === focus) {
                    this.style.display = "inline";
                    displayButtons();
                }
            });

            function displayButtons(){
                focus.height === 0 ?
                        (
                            applyToCourse.className = avail,
                            applyFullSpec.className = avail,
                            applyToCourse.href = './redirect.php?courseId=' + focus.data.id
                        )
                        :
                        (
                            applyToCourse.className = dis,
                            applyFullSpec.className = dis
                        );
            }
    }

    function zoomTo(v) {
        var k = diameter / v[2];
        view = v;
        node.attr("transform", function (d) {
            return "translate(" + (d.x - v[0]) * k + "," + (d.y - v[1]) * k + ")";
        });
        circle.attr("r", function (d) {
            return d.r * k;
        });
    }

    function displayInfo(data) {
        superclustP.innerHTML = '',
        subclustP.innerHTML = '';
        // check if it is course
        data.height != 0 ? // if not display subclusters and hide skills
            (
                subclustDiv.style.display = 'inline',
                skillsDiv.style.display = 'none',
                data.children.forEach((elem) => {
                    var li = lists(elem.data);
                    subclustP.appendChild(li);
                })
            )
            : // if yes hide subclusters and display skills
            (
                subclustDiv.style.display = 'none',
                skillsDiv.style.display = 'inline',
                fillFields(data.data)
            );
        // check if it is root
        data.depth != 0 ?
            (        
                superclustDiv.style.display = 'inline',
                repeat(data)
            ) 
            :
            (
                superclustDiv.style.display = 'none'
            ) ;
        titleP.innerHTML = '[' + data.data.short_name + '] ' + data.data.long_name;
        descrP.innerHTML = data.data.description;

        // get superclusters as parent field
        function repeat(data) {
            console.log(data);
            var height = data.depth;
            var parentObj = data.parent;
            var parentArray = [];
            while (height > 0) {
                parentArray.push(parentObj.data.long_name);
                parentObj = parentObj.parent;
                height--;
            }
            
            // Change the way that 'superclusters' appear
            for (var index = parentArray.length - 1; index > -1; index--) {                
                var link = document.createElement('a');
                link.href = '#';
                link.innerHTML = (index == 0) ? parentArray[index] : parentArray[index] + '>';
                link.addEventListener("click", (e) => {
                    var name = e.target.text.replace(/[^a-zA-Z0-9 ]/g, '');
                    var result = fullObj.find(item =>item.data.long_name === name)
                    if (result) {
                        zoom(result);
                        searchChildren.style.display = "none";
                        displayInfo(result);
                    }
                });
                link.className = '';
                link.style = "text-decoration: none;";
                superclustP.append(link);
                superclustDiv.style = 'margin-bottom: 20px';
            }
        }
    }


    function lists (element) {
        var li = document.createElement('li');
        var link = document.createElement('a');
        link.href = '#';
        link.innerHTML = '[' + element.short_name + '] ' + element.long_name + '<br />';
        link.addEventListener("click", () => {
            var result = fullObj.find(item =>item.data.long_name === element.long_name)
            if (result) {
                zoom(result);
                searchChildren.style.display = "none";
                displayInfo(result);
            }
        });
        link.className = 'elementor-sub-item';
        li.className = "menu-item menu-item-type-post_type menu-item-object-page menu-item-6800";
        link.style = "text-decoration: none;";
        li.style = "list-style: none;";
        li.appendChild(link);

        return li;
    }

    // Fills the course fields on landing page
    function fillFields (index) {
        
        for (var key in skillsDiv.children) {
                var field = skillsDiv.children[key].className;
                if (skillsDiv.children[key].className == 'subjects') {
                    skillsDiv.children[key].children[0].children[0].innerHTML = '';
                    <?php
                    // Get clusters from database
                    $query1 = mysqli_query($db, 'SELECT us.scuid, s.name
                                                from `subject` s, `scu` u, `scu_subject` us
                                                where u.id = us.scuid
                                                and s.id = us.subjectid
                                                order by us.class_time + us.autonomous_time desc');

                    if ($query1->num_rows > 0) {
                        while ($row = mysqli_fetch_object($query1)) { ?>
                            if (index['id'] === <?php echo '"'.$row->scuid.'"'; ?>) {
                                var li = document.createElement('li');
                                li.innerHTML = <?php echo '"'.$row->name.'"'; ?>;
                                li.style = "list-style: none;";
                                skillsDiv.children[key].children[0].children[0].append(li); // the field of the index corresponding to the class of the div
                            }
                        <?php
                        }
                    } else { ?>
                        console.log('No subjects were found...');
                        <?php
                    }
                    ?>
                }
                else if (skillsDiv.children[key].className == 'learning_objectives') {
                    skillsDiv.children[key].children[0].children[0].innerHTML = '';
                    <?php
                    // Get clusters from database
                    $query1 = mysqli_query($db, 'SELECT u.id, lo.description
                                                from scu u, learning_outcome lo
                                                where lo.scuid = u.id
                                                order by lo.description');

                    if ($query1->num_rows > 0) {
                        while ($row = mysqli_fetch_object($query1)) { ?>
                            if (index['id'] === <?php echo '"'.$row->id.'"'; ?>) {
                                var li = document.createElement('li');
                                li.innerHTML = <?php echo '"'.$row->description.'"'; ?>;
                                li.style = "list-style: none;";
                                skillsDiv.children[key].children[0].children[0].append(li); // the field of the index corresponding to the class of the div
                            }                        
                        <?php
                        }
                    } else { ?>
                        console.log('No learning objectives were found...');
                        <?php
                    }
                    ?>
                }
                else if (skillsDiv.children[key].className == 'ects') {
                    skillsDiv.children[key].children[0].children[0].innerHTML = index[field]; // the field of the index corresponding to the class of the div
                }
                else if (skillsDiv.children[key].className == 'total_work_hours') {
                    skillsDiv.children[key].children[0].children[0].innerHTML = index[field]; // the field of the index corresponding to the class of the div
                }
        }


    }

    function searchF(data) {

        var filter = data.toUpperCase();
        var searchChildren = document.getElementById("searchChildren");
        var ul = document.getElementById("searchItems");
        var li = ul.getElementsByTagName("li");

        for (var i = 1; i < li.length; i++) {
            var a = li[i].innerText;
            if (a.toUpperCase().indexOf(filter) > -1) 
                li[i].style.display = "";
            else 
                li[i].style.display = "none";
        }
        searchChildren.style.display = "inline";
    }

    function print(event){
        event.type != 'click' ? 
            document.getElementById("searchChildren").style.display = 'inline'
        :
            document.getElementById("searchChildren").style.display = 'none';
            
    }

</script>