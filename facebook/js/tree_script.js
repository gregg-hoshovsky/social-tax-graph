	var currentNode;
	$(function() {
		// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
		$( "#dialog:ui-dialog" ).dialog( "destroy" );
		
		var name = $( "#name" ),
			email = $( "#email" ),

			allFields = $( [] ).add( name ).add( email ),
			tips = $( ".validateTips" );

		function updateTips( t ) {
			tips
				.text( t )
				.addClass( "ui-state-highlight" );
			setTimeout(function() {
				tips.removeClass( "ui-state-highlight", 1500 );
			}, 500 );
		}

		function checkLength( o, n, min, max ) {
			if ( o.val().length > max || o.val().length < min ) {
				o.addClass( "ui-state-error" );
				updateTips( "Length of " + n + " must be between " +
					min + " and " + max + "." );
				return false;
			} else {
				return true;
			}
		}

		function checkRegexp( o, regexp, n ) {
			if ( !( regexp.test( o.val() ) ) ) {
				o.addClass( "ui-state-error" );
				updateTips( n );
				return false;
			} else {
				return true;
			}
		}
		
		$( "#dialog-form" ).dialog({
			autoOpen: false,
			height: 300,
			width: 350,
			modal: true,
			buttons: {
				"Create an account": function() {
					var bValid = true;
					allFields.removeClass( "ui-state-error" );

					bValid = bValid && checkLength( name, "caregory", 1, 80 );
					bValid = bValid && checkLength( email, "percent", 1, 3 );

					bValid = bValid && checkRegexp( name, /^[a-z]([0-9a-z_])+$/i, "Username may consist of a-z, 0-9, underscores, begin with a letter." );
					bValid = bValid && checkRegexp( email,  /^([0-9\.])+$/, "Between 1 and 100" );

					if ( bValid ) {
						var title =name;
						var id=email.val();
						var percent=email.val();
						var  	obj_js =  {
							"data":{"title":name.val() + ":" + percent},
							"metadata" : { "id" : id, "percent": 10},
							"state":""					
							};
						$( this ).dialog( "close" );
						$("#demo").jstree("create", currentNode, "last", obj_js,false,true);

					}
					
				},
				Cancel: function() {
					$( this ).dialog( "close" );
				}
			},
			close: function() {
				allFields.val( "" ).removeClass( "ui-state-error" );
			}
		});

		$( "#create-user" )
			.button()
			.click(function() {
				$( "#dialog-form" ).dialog( "open" );
			});
	});

$(function () {

$("#demo")
	.bind("before.jstree", function (e, data) {
		$("#alog").append(data.func + "<br />");
	})
	.jstree({ 
		// List of active plugins
		"plugins" : [ 
			"themes","json_data","ui","crrm","cookies","dnd","search","types"
		],
		"dnd" : {
			"drop_finish" : function () { 
				alert("DROP"); 
			},
			"drag_check" : function (data) {
				if(data.r.attr("id") == "phtml_1") {
					return false;
				}
				return { 
					after : false, 
					before : false, 
					inside : true 
				};
			},
			"drag_finish" : function (data) { 
				alert("DRAG OK"); 
			}
		},
		// I usually configure the plugin that handles the data first
		// This example uses JSON as it is most common
		"json_data" : { 
		"data": tree_data
		
		},

		// Using types - most of the time this is an overkill
		// read the docs carefully to decide whether you need types
		"types" : {
			// I set both options to -2, as I do not need depth and children count checking
			// Those two checks may slow jstree a lot, so use only when needed
			"max_depth" : -2,
			"max_children" : -2,
			// I want only `drive` nodes to be root nodes 
			// This will prevent moving or creating any other type as a root node
			"valid_children" : [ "drive" ],
			"types" : {
				// The default type
				"default" : {
					"valid_children" : [ "default", "folder" ],
					"icon" : {
						"image" : "./folder.png"
					}
				},
				// The `folder` type
				"folder" : {
					// can have files and other folders inside of it, but NOT `drive` nodes
					"valid_children" : [ "default", "folder" ],
					"icon" : {
						"image" : "./folder.png"
					}
				},
				// The `drive` nodes 
				"drive" : {
					// can have files and folders inside, but NOT other `drive` nodes
					"valid_children" : [ "default", "folder" ],
					"icon" : {
						"image" : "./root.png"
					},
					// those prevent the functions with the same name to be used on `drive` nodes
					// internally the `before` event is used
					"start_drag" : false,
					"move_node" : false,
					"delete_node" : false,
					"remove" : false
				}
			}
		},
		// UI & core - the nodes to initially select and open will be overwritten by the cookie plugin

		// the UI plugin - it handles selecting/deselecting/hovering nodes
		"ui" : {
			// this makes the node with ID node_4 selected onload
			"initially_select" : [ "node_4" ]
		},
		// the core plugin - not many options here
		"core" : { 
			// just open those two nodes up
			// as this is an AJAX enabled tree, both will be downloaded from the server
			"initially_open" : [ "node_2" , "node_3" ] 
		}
	})
	.bind("create.jstree", function (e, data) {

	})
	.bind("remove.jstree", function (e, data) {
		data.rslt.obj.remove(); 
	})
	.bind("rename.jstree", function (e, data) {
		$.post(
			"./server.php", 
			{ 
				"operation" : "rename_node", 
				"id" : data.rslt.obj.attr("id").replace("node_",""),
				"title" : data.rslt.new_name
			}, 
			function (r) {
				if(!r.status) {
					$.jstree.rollback(data.rlbk);
				}
			}
		);
	})
	.bind("select_node.jstree", function (e, data) 
	{ 
		currentNode=$('#demo').jstree('get_selected');
	});


});

// Code for the menu buttons
$(function () { 
	$("#mmenu input").click(function () {
		switch(this.id) {
			case "add_default":
			case "add_folder":
				$( "#dialog-form" ).dialog( "open" );
				break;
			case "search":
				//alert("TARGEt:"+document.getElementById("text").value);
				$("#demo").jstree("search", document.getElementById("text").value);
				$("#demo2").jstree("search", document.getElementById("text").value);
				break;
			case "text": break;
			default:
				$("#demo").jstree(this.id);
				break;
		}
	});
});

$(function () {
	$("#demo2")
	.jstree({ 

		"json_data" : { 
		"data": tree_data
		
		},
		"dnd" : {
			"drop_finish" : function () { 
				alert("DROP"); 
			},
			"drag_check" : function (data) {
				if(data.r.attr("id") == "phtml_1") {
					return false;
				}
				return { 
					after : false, 
					before : false, 
					inside : true 
				};
			},
			"drag_finish" : function (data) { 
				alert("DRAG OK"); 
			}
		},
				// List of active plugins
		"plugins" : [ 
			"themes","json_data","ui","crrm","cookies","dnd","search","types"
		],

	})
		.bind("create.jstree", function (e, data) {


	})
	.bind("remove.jstree", function (e, data) {
		data.rslt.obj.remove(); 
	})
	.bind("rename.jstree", function (e, data) {
		$.post(
			"./server.php", 
			{ 
				"operation" : "rename_node", 
				"id" : data.rslt.obj.attr("id").replace("node_",""),
				"title" : data.rslt.new_name
			}, 
			function (r) {
				if(!r.status) {
					$.jstree.rollback(data.rlbk);
				}
			}
		);
	})
	.bind("select_node.jstree", function (e, data) 
	{ 

	});

});


