const svgContainer = document.querySelector("#svgContainer");
const subclustDiv = document.querySelector(".subclusters");
const skillsDiv = document.querySelector(".skills");
const teachersDiv = document.querySelector(".teachers");
const titleP = document.querySelector("#title");
const descrP = document.querySelector(".desc p");
const subclustP = document.querySelector(".subclusters ul");
const skillsP = document.querySelector(".skills p");
const teachersP = document.querySelector(".teachers p");
const full_names = document.querySelector("#full");
const short_names = document.querySelector("#short");
const applyToCourse = document.getElementById("ApplyToCourse");
const applyFullSpec = document.getElementById("ApplyFullSpec");

const ul = document.querySelector("#searchItems");
const searchChildren = document.querySelector("#searchChildren");

var clusterArr = {
    name: 'Competence Clusters',
    children: []
};

var fullObj;
var bool = false;

// ~~~~~~~~~~~~~~~~~~~~~ FILE READER ~~~~~~~~~~~~~~~~~~~~~~~~~~~

var url = ".ExcelFiles/CC_taxonomy.xlsx";
var oReq = new XMLHttpRequest();
oReq.open("GET", url, true);
oReq.responseType = "arraybuffer";

oReq.onload = function (e) {
    var arraybuffer = oReq.response;

    /* convert data to binary string */
    var data = new Uint8Array(arraybuffer);
    var arr = new Array();
    for (var i = 0; i != data.length; ++i) arr[i] = String.fromCharCode(data[i]);
    var bstr = arr.join("");

    /* Call XLSX */
    var workbook = XLSX.read(bstr, {
        type: "binary"
    });

    /* DO SOMETHING WITH workbook HERE */
    var first_sheet_name = workbook.SheetNames[0];
    /* Get worksheet */
    var worksheet = workbook.Sheets[first_sheet_name];
    var clusters = XLSX.utils.sheet_to_json(worksheet, {
        raw: true
    });

    clusters.map(({ Cluster, Subcluster, Course, Programme, Teacher_name, Teacher_email, Role, Institution }) => {

        if (Subcluster === undefined) Subcluster = getFirstLetters(Cluster) + ' Sub'; // Important only if the Subcluster doesn't have a name

        clusters = clusterArr.children;
        var foundCluster = clusters.find(item => item.name === Cluster);
        var foundSubcluster = undefined;
        if (clusters.length > 0) foundSubcluster = clusters[clusters.length - 1].children.find(item => item.name === Subcluster);

        !foundCluster ?
            // Creation of cluster if not existing already
            (
                clusters.push({
                    id: 'Cluster',
                    name: Cluster,
                    children: [
                        !foundSubcluster ?
                            (
                                // case "Not found cluster, not found subcluster" - Creation of cluster & subcluster for the first time
                                {
                                    case: 'NOT found Cluster',
                                    id: 'Subcluster',
                                    name: Subcluster,
                                    size: 1,
                                    children: [
                                        {
                                            case: '1st',
                                            id: 'Course',
                                            name: Course,
                                            programme: Programme,
                                            teacher_name: Teacher_name,
                                            teacher_email: Teacher_email,
                                            teacher_role: Role,
                                            institution: Institution,
                                            size: 2
                                        }
                                    ]
                                }
                            )
                            :
                            (
                                // case "Not found cluster, found subcluster" - Adding to existing subcluster inside of not existing cluster
                                {
                                    case: 'NOT found Cluster',
                                    id: 'Subcluster',
                                    name: Subcluster,
                                    size: 1,
                                    children: [
                                        {
                                            case: 'Something is wrong'
                                        }
                                    ]
                                }
                            )
                    ]
                })
            ) :
            // Adding to existing cluster
            (
                // console.log(foundCluster, clusters),
                !foundSubcluster ?
                    foundCluster.children.push({
                        case: 'Found cluster',
                        name: Subcluster,
                        size: 1,
                        children: [
                            // case "Found cluster, not found subcluster" - Creation of subcluster for the first time inside of existing cluster
                            {
                                case: '3rd',
                                id: 'Course',
                                name: Course,
                                programme: Programme,
                                teacher_name: Teacher_name,
                                teacher_email: Teacher_email,
                                teacher_role: Role,
                                institution: Institution,
                                size: 2,
                            }
                        ]
                    })
                    :
                    foundSubcluster.children.push(
                        // case "Found cluster, found subcluster" - Adding to existing subcluster inside of existing cluster
                        {
                            case: '4th',
                            id: 'Course',
                            name: Course,
                            programme: Programme,
                            teacher_name: Teacher_name,
                            teacher_email: Teacher_email,
                            teacher_role: Role,
                            institution: Institution,
                            size: 2,

                        })

            );
    });

    console.log(clusterArr);

    chart(clusterArr);

    // close searchChildren
    var li = document.createElement('li');
    var link = document.createElement('a');
    link.href = '#';
    var img = document.createElement('img');
    img.src = '/assets/icons/close.png';
    link.innerHTML = 'Cancel';
    link.appendChild(img);
    link.addEventListener("click", () => {
        document.getElementById("searchChildren").style.display = 'none';
    });
    link.className = 'elementor-sub-item';
    li.className = "menu-item menu-item-type-post_type menu-item-object-page menu-item-6800 font-weight-bold text-right";
    link.style = "text-decoration: none;";
    li.appendChild(link);
    ul.appendChild(li);

    clusterArr.children.forEach((data) => {
        var name = data.name;
        var li = document.createElement('li');
        var link = document.createElement('a');
        link.href = '#';
        link.innerHTML = name;
        link.addEventListener("click", () => {
            var result = fullObj.find(item => item.data.name === name)
            if (result) {
                zoom(result);
                searchChildren.style.display = "none";
                displayInfo(result);
            }
        });
        link.className = 'elementor-sub-item';
        li.className = "menu-item menu-item-type-post_type menu-item-object-page menu-item-6800";
        link.style = "text-decoration: none;";
        li.appendChild(link);
        ul.appendChild(li);
    })

    full_names.addEventListener("click", () => {
        bool = true;
        console.log("<<<<<<<<<<<<<<<<<<Long", bool);
        chart(clusterArr);
    });

    short_names.addEventListener("click", () => {
        bool = false;
        console.log("<<<<<<<<<<<<<<<<<Short", bool);
        chart(clusterArr);
    });
}

oReq.send();

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
    var rainbow = ["#6e40aa", "#7140ab", "#743fac", "#773fad", "#7a3fae", "#7d3faf", "#803eb0", "#833eb0", "#873eb1", "#8a3eb2", "#8d3eb2", "#903db2", "#943db3", "#973db3", "#9a3db3", "#9d3db3", "#a13db3", "#a43db3", "#a73cb3", "#aa3cb2", "#ae3cb2", "#b13cb2", "#b43cb1", "#b73cb0", "#ba3cb0", "#be3caf", "#c13dae", "#c43dad", "#c73dac", "#ca3dab", "#cd3daa", "#d03ea9", "#d33ea7", "#d53ea6", "#d83fa4", "#db3fa3", "#de3fa1", "#e040a0", "#e3409e", "#e5419c", "#e8429a", "#ea4298", "#ed4396", "#ef4494", "#f14592", "#f34590", "#f5468e", "#f7478c", "#f9488a", "#fb4987", "#fd4a85", "#fe4b83", "#ff4d80", "#ff4e7e", "#ff4f7b", "#ff5079", "#ff5276", "#ff5374", "#ff5572", "#ff566f", "#ff586d", "#ff596a", "#ff5b68", "#ff5d65", "#ff5e63", "#ff6060", "#ff625e", "#ff645b", "#ff6659", "#ff6857", "#ff6a54", "#ff6c52", "#ff6e50", "#ff704e", "#ff724c", "#ff744a", "#ff7648", "#ff7946", "#ff7b44", "#ff7d42", "#ff8040", "#ff823e", "#ff843d", "#ff873b", "#ff893a", "#ff8c38", "#ff8e37", "#fe9136", "#fd9334", "#fb9633", "#f99832", "#f89b32", "#f69d31", "#f4a030", "#f2a32f", "#f0a52f", "#eea82f", "#ecaa2e", "#eaad2e", "#e8b02e", "#e6b22e", "#e4b52e", "#e2b72f", "#e0ba2f", "#debc30", "#dbbf30", "#d9c131", "#d7c432", "#d5c633", "#d3c934", "#d1cb35", "#cece36", "#ccd038", "#cad239", "#c8d53b", "#c6d73c", "#c4d93e", "#c2db40", "#c0dd42", "#bee044", "#bce247", "#bae449", "#b8e64b", "#b6e84e", "#b5ea51", "#b3eb53", "#b1ed56", "#b0ef59", "#adf05a", "#aaf159", "#a6f159", "#a2f258", "#9ef258", "#9af357", "#96f357", "#93f457", "#8ff457", "#8bf457", "#87f557", "#83f557", "#80f558", "#7cf658", "#78f659", "#74f65a", "#71f65b", "#6df65c", "#6af75d", "#66f75e", "#63f75f", "#5ff761", "#5cf662", "#59f664", "#55f665", "#52f667", "#4ff669", "#4cf56a", "#49f56c", "#46f46e", "#43f470", "#41f373", "#3ef375", "#3bf277", "#39f279", "#37f17c", "#34f07e", "#32ef80", "#30ee83", "#2eed85", "#2cec88", "#2aeb8a", "#28ea8d", "#27e98f", "#25e892", "#24e795", "#22e597", "#21e49a", "#20e29d", "#1fe19f", "#1edfa2", "#1ddea4", "#1cdca7", "#1bdbaa", "#1bd9ac", "#1ad7af", "#1ad5b1", "#1ad4b4", "#19d2b6", "#19d0b8", "#19cebb", "#19ccbd", "#19cabf", "#1ac8c1", "#1ac6c4", "#1ac4c6", "#1bc2c8", "#1bbfca", "#1cbdcc", "#1dbbcd", "#1db9cf", "#1eb6d1", "#1fb4d2", "#20b2d4", "#21afd5", "#22add7", "#23abd8", "#25a8d9", "#26a6db", "#27a4dc", "#29a1dd", "#2a9fdd", "#2b9cde", "#2d9adf", "#2e98e0", "#3095e0", "#3293e1", "#3390e1", "#358ee1", "#378ce1", "#3889e1", "#3a87e1", "#3c84e1", "#3d82e1", "#3f80e1", "#417de0", "#437be0", "#4479df", "#4676df", "#4874de", "#4a72dd", "#4b70dc", "#4d6ddb", "#4f6bda", "#5169d9", "#5267d7", "#5465d6", "#5663d5", "#5761d3", "#595fd1", "#5a5dd0", "#5c5bce", "#5d59cc", "#5f57ca", "#6055c8", "#6153c6", "#6351c4", "#6450c2", "#654ec0", "#664cbe", "#674abb", "#6849b9", "#6a47b7", "#6a46b4", "#6b44b2", "#6c43af", "#6d41ad", "#6e40aa"];

    // Setting information on landing
    while (subclustP.firstChild) {
        subclustP.removeChild(subclustP.lastChild);
    }
    for (const key in root.children) {
        const element = root.children[key];
        titleP.innerHTML = '[' + getFirstLetters(root.name) + '] ' + root.name;

        var li = document.createElement('li');
        var link = document.createElement('a');
        link.href = '#';
        link.innerHTML = '[' + getFirstLetters(element.name) + '] ' + element.name + '<br />';
        link.addEventListener("click", () => {
            var result = fullObj.find(item => item.data.name === element.name)
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
        subclustP.appendChild(li);
    }

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
        let newcolor = multicolor ? d3.hsl(color(obj.data.name)) : d3.hsl(hexcolor);
        newcolor.l -= depth == 1 ? 0 : depth * 0.1;

        if (depth == 0) newcolor = '#999';
        return newcolor;
    }

    function setStrokeColor(obj) {
        let depth = obj.depth;
        while (obj.depth > 1) {
            obj = obj.parent;
        }
        let strokecolor = multicolor ? d3.hsl(color(obj.data.name)) : d3.hsl(hexcolor);
        return strokecolor;
    }

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
                Name = getFirstLetters(d.data.name)
                :
                Name = d.data.name;
            // console.log(bool, Name)
            return Name;
        });

    node = g.selectAll("circle,text");

    zoomTo([root.x, root.y, root.r * 2 + margin]);
}

function zoom(d) {
    focus = d;

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

    const getCircularReplacer = () => {
        const seen = new WeakSet();
        return (key, value) => {
            if (typeof value === "object" && value !== null) {
                if (seen.has(value)) {
                    return;
                }
                seen.add(value);
            }
            return value;
        };
    };

    transition
        .selectAll("text")
        .style("fill-opacity", function (d) {
            return d.parent === focus ? 1 : focus.height == 0 ? 1 : 0;
        })
        .style("font-size", function (d) {
            return d.parent === focus ? "14px" : focus.height == 0 ? "16px" : "12px";
        })
        .on("start", function (d) {

            var avail = 'site-button';
            var dis = 'site-button isDisabled';

            if (d.parent === focus) {
                this.style.display = "inline";
                focus.height === 0 ?
                    (
                        applyToCourse.className = avail,
                        applyFullSpec.className = avail,

                        localStorage.setItem('info', JSON.stringify(focus, getCircularReplacer()))
                    )
                    :
                    (
                        applyToCourse.className = dis,
                        applyFullSpec.className = dis
                    );
            }
            else if (d === focus && focus.height === 0) {
                this.style.display = "inline";
                applyToCourse.className = avail;
                applyFullSpec.className = avail;
                localStorage.setItem('info', JSON.stringify(focus, getCircularReplacer()));
            }
        })
        .on("end", function (d) {

            var avail = 'site-button';
            var dis = 'site-button isDisabled';

            if (d === focus) {
                this.style.display = "inline";
                focus.height === 0 ?
                    (
                        applyToCourse.className = avail,
                        applyFullSpec.className = avail,
                        localStorage.setItem('info', JSON.stringify(focus, getCircularReplacer()))
                    )
                    :
                    (
                        applyToCourse.className = dis,
                        applyFullSpec.className = dis
                    );
            }
            else {
                this.style.display = "none";
                console.log('mphke3')
                focus.height === 0 ?
                    (
                        applyToCourse.className = avail,
                        applyFullSpec.className = avail,
                        localStorage.setItem('info', JSON.stringify(focus, getCircularReplacer()))
                    )
                    :
                    (
                        applyToCourse.className = dis,
                        applyFullSpec.className = dis
                    );
            }

            if (d.parent === focus) {
                this.style.display = "inline";
                console.log('mphke4')
                focus.height === 0 ?
                    (
                        applyToCourse.className = avail,
                        applyFullSpec.className = avail,
                        localStorage.setItem('info', JSON.stringify(focus, getCircularReplacer()))
                    )
                    :
                    (
                        applyToCourse.className = dis,
                        applyFullSpec.className = dis
                    );
            }
        });
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
    descrP.innerHTML = skillsP.innerHTML = teachersP.innerHTML = subclustP.innerHTML = '';
    data.height != 0 ?
        (
            subclustDiv.style.display = 'inline',
            skillsDiv.style.display = 'none',
            teachersDiv.style.display = 'none',
            data.children.forEach((elem) => {
                var li = document.createElement('li');
                var link = document.createElement('a');
                link.href = '#';
                link.innerHTML = '[' + getFirstLetters(elem.data.name) + '] ' + elem.data.name + '<br />';
                link.addEventListener("click", () => {
                    var result = fullObj.find(item => item.data.name === elem.data.name)
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
                subclustP.appendChild(li);
            })
        )
        :
        (
            subclustDiv.style.display = 'none',
            skillsDiv.style.display = 'inline',
            teachersDiv.style.display = 'inline',
            teachersP.innerHTML += data.data.teacher_name + '\n'

        );
    titleP.innerHTML = '[' + getFirstLetters(data.data.name) + '] ' + data.data.name;
}

function getFirstLetters(str) {
    if (str === undefined) str = '';
    const firstLetters = str.slice(0, str.indexOf('(')).split(' ').map(word => word[0]).join('');
    return firstLetters;
}
