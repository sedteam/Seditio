//Load button actions for HTML Mode

Tb3.conf.html = {
	onCtrlEnter: {open: "\n<br/>"},
	onShiftEnter: {open: "\n<p>", close: "</p>"},
	preview: {
		parser: 'plug.php?ajx=textboxer3&mode=html',
		autoRefresh: true
	},
	markup: [
	{
			name: 'bold',
			className: 'strong',			
			title: 'Bold',
			open: '<strong>',
			close: '</strong>',
			key: 'B',
			alt: {
				open: '<b>',
				close: '</b>'
			}
		},
		{
			name: 'italic',
			title: 'Italic',
			key: 'I',
			open: '<em>',
			close: '</em>'
		},
		{
			name: 'underline',
			title: 'Underline',
			key: 'U',
			open: '<u>',
			close: '</u>'
		},		
		{
			name: 'del',
			title: 'Strike Through',
			open: '<del>',
			close: '</del>'
		},
		{
			separator: true	
		},				
		{
			name: 'h',
			title: 'Heading',
			dropDownMenu: [
      		{
      			name: 'h1',
      			title: 'Heading 1',
      			open: '<h1>',
      			close: '</h1>',
      			prepend: "\n",
      			placeholder: 'Heading 1'
      		},
      		{
      			name: 'h2',
      			title: 'Heading 2',
      			open: '<h2>',
      			close: '</h2>',
      			prepend: "\n"
      		},
      		{
      			name: 'h3',
      			title: 'Heading 3',
      			open: '<h3>',
      			close: '</h3>',
      			prepend: "\n"
      		},
      		{
      			name: 'h4',
      			title: 'Heading 4',
      			open: '<h4>',
      			close: '</h4>',
      			prepend: "\n"
      		},
      		{
      			name: 'h5',
      			title: 'Heading 5',
      			open: '<h5>',
      			close: '</h5>',
      			prepend: "\n"
      		},
      		{
      			name: 'h6',
      			title: 'Heading 6',
      			open: '<h6>',
      			close: '</h6>',
      			prepend: "\n"
      		}
			]
		},
		
		{
			name: 'htags',
			title: 'Other tags',
			className: 'htags',      
			dropDownMenu: [
				{
					name: 'p',
					className: 'p',
					title: 'Paragraph',
					open: '<p>',
					close: '</p>'
        }, 
				{
					name: 'hr',
					className: 'hr',
					title: 'Horizontal line',
					open: '<hr>',
					close: ''
        },
				{
					name: 'spacer',
					className: 'spacer',
					title: 'Non breaking space',
					open: '&nbsp;',
					close: ''
        },
    		{
    			name: 'ac',
			    className: 'ac',
    			title: 'Acronym',
    			open: '<acronym title="{acronym}">{explanation}',
    			close: '</acronym>',
    			attributes: [
    				{
    					type: 'text',
    					name: 'acronym',
    					label: 'Acronym'
    				},
            {
    					type: 'text',
    					name: 'explanation',
    					label: 'Explanation'            
            }           
    			]
    		},        
				{
					name: 'spoiler',
					className: 'spoiler',
					title: 'Spoiler',
					open: '<div style="margin:0; margin-top:8px"><div style="margin-bottom:4px"><input type="button" class="submit btn" value="Show" onClick="if (this.parentNode.parentNode.getElementsByTagName(\'div\')[1].getElementsByTagName(\'div\')[0].style.display != \'\') { this.parentNode.parentNode.getElementsByTagName(\'div\')[1].getElementsByTagName(\'div\')[0].style.display = \'\'; this.value = \'Hide\'; } else { this.parentNode.parentNode.getElementsByTagName(\'div\')[1].getElementsByTagName(\'div\')[0].style.display = \'none\'; this.value = \'Show\'; }"></div><div class="spoiler"><div style="display: none;">',
					close: '</div></div></div>'
        }         
      ]
		},		
		{
			name: 'aligns',
			className: 'align',
			title: 'Alignments',
			dropDownMenu: [
				{
					name: 'align-center',
					className: 'align-center',
					title: 'Align Center',
					open: '<div style="text-align:center">',
					close: '</div>'
				},
				{
					name: 'align-left',
					className: 'align-left',
					title: 'Align Left',
					open: '<div style="text-align:left">',
					close: '</div>'
				},
				{
					name: 'align-right',
					className: 'align-right',
					title: 'Align Right',
					open: '<div style="text-align:right">',
					close: '</div>'
				},
				{
					name: 'align-justify',
					className: 'align-justify',
					title: 'Align Justify',
					open: '<div style="text-align:justify">',
					close: '</div>'
				}
			]
		},
		{separator: true},
		{				
		open: '<img{attributes}/>',
		name: 'img',
		title: 'Image',
		attributes: [
			{
				type: 'text',
				name: 'src',
				label: 'Image URL'
			},
			{
				type: 'text',
				name: 'width',
				label: 'Width'
			},
			{
				type: 'text',
				name: 'height',
				label: 'Height'
			},
			{
				type: 'text',
				name: 'alt',
				label: 'Alt'
			},
			{
				type: 'list',
				name: 'align',
				label: 'Align',
				list: {left: 'Left', middle: 'Middle', right: 'Right'}
			}
			]
		},
		{
			name: 'thumb',
			title: 'Thumbnail',
			open: '<a href="{burl}" title="{title}"><img src="{turl}" alt=\"{alt}\"/>',
			close: '</a>',
			attributes: [
				{
					type: 'text',
					name: 'turl',
					label: 'Thumbnail URL'
				},
				{
					type: 'text',
					name: 'alt',
					label: 'Alt'
				},
				{
					type: 'text',
					name: 'burl',
					label: 'Big Image URL'
				},
				{
					name: 'title',
					type: 'text',
					label: 'Title'
				}
			]
		},		
		{
			name: 'columns',
			className: 'columns',
			title: 'Images & Columns',
			dropDownMenu: [
				{
					name: 'column-right',
					className: 'column-right',
					title: 'Column Right',
					open: '<div class="colright">',
					close: '</div>'
				},
				{
					name: 'column-left',
					className: 'column-left',
					title: 'Column Left',
					open: '<div class="colleft">',
					close: '</div>'
				}
			]	
		},
		{
			name: 'urls',
			className: 'a',
			title: 'Link and Email',
			dropDownMenu: [
				{
					open: '<a{attributes}>',
					close: '</a>',
					name: 'url',
					title: 'Link',
					attributes: [
						{
							name: 'href',
							type: 'text',
							label: 'Link URL'
						},
						{
							name: 'target',
							type: 'list',
							label: 'Target',
							list: {'': '', '_self': '_self', '_parent': '_parent', '_blank': '_blank'}
						},
						{
							name: 'title',
							type: 'text',
							label: 'Title'
						},
						{
							name: 'class',
							type: 'text',
							label: 'Class'
						}			
					]
				},
    		{
    			name: 'email',
			    className: 'email',
    			title: 'E-mail',
    			open: '<a href="mailto:{email}">{etitle}',
    			close: '</a>',
    			attributes: [
    				{
    					type: 'text',
    					name: 'email',
    					label: 'Email'
    				},
            {
    					type: 'text',
    					name: 'etitle',
    					label: 'Text email'            
            }           
    			]
    		}
			]
		},		
    Tb3.addons.hcolorPicker,
		{
			name: 'video',
			title: 'Insert Video',
			className: 'video',      
			dropDownMenu: [
    		{
    			name: 'youtube',
			    className: 'youtube',
    			title: 'Youtube video',
    			open: '<iframe width="425" height="300" src="//www.youtube.com/embed/{videoid}" frameborder="0" allowfullscreen></iframe>',
    			close: '',
    			attributes: [
    				{
    					type: 'text',
    					name: 'videoid',
    					label: 'Video #ID',
    					help: 'Enter part *** URL http://www.youtube.com/watch?v=(***)'
    				}       
    			]
    		},
    		{
    			name: 'vk',
			    className: 'vk',
    			title: 'Vkontakte video',
    			open: '<iframe src="http://vkontakte.ru/video_ext.php?oid={videoid}" width="425" height="350" frameborder="0"></iframe>',
    			close: '',
    			attributes: [
    				{
    					type: 'text',
    					name: 'videoid',
    					label: 'Video #ID',
    					help: 'Enter part *** URL http://vkontakte.ru/video_ext.php?oid=(***)'
    				}       
    			]
    		},
    		{
    			name: 'dailymotion',
			    className: 'dailymotion',
    			title: 'Dailymotion video',
    			open: '<iframe width="425" height="300" src="http://www.dailymotion.com/embed/video/{videoid}" frameborder="0" allowfullscreen></iframe>',
    			close: '',
    			attributes: [
    				{
    					type: 'text',
    					name: 'videoid',
    					label: 'Video #ID',
    					help: 'Enter part *** URL http://www.dailymotion.com/embed/video/(***)'
    				}       
    			]
    		},
    		{
    			name: 'vimeo',
			    className: 'vimeo',
    			title: 'Vimeo video',
    			open: '<iframe src="http://player.vimeo.com/video/{videoid}" width="425" height="281" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>',
    			close: '',
    			attributes: [
    				{
    					type: 'text',
    					name: 'videoid',
    					label: 'Video #ID',
    					help: 'Enter part *** URL http://player.vimeo.com/video/(***)'
    				}       
    			]
    		},
    		{
    			name: 'metacafe',
			    className: 'metacafe',
    			title: 'MetaCafe video',
    			open: '<iframe src="http://www.metacafe.com/embed/{videoid}/" width="425" height="248" allowFullScreen frameborder=0></iframe>',
    			close: '',
    			attributes: [
    				{
    					type: 'text',
    					name: 'videoid',
    					label: 'Video #ID',
    					help: 'Enter part *** URL http://www.metacafe.com/fplayer/(***)'
    				}       
    			]
    		},
    		{
    			name: 'rutube',
			    className: 'rutube',
    			title: 'Rutube video',
    			open: '<iframe width="425" height="281" src="http://rutube.ru/video/embed/{videoid}" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowfullscreen></iframe>',
    			close: '',
    			attributes: [
    				{
    					type: 'text',
    					name: 'videoid',
    					label: 'Video #ID',
    					help: 'Enter part *** URL http://video.rutube.ru/(***)'
    				}       
    			]
    		}  
			]
		},			
		{
			name: 'stags',
			title: 'Seditio tags',
			className: 'stags',      
			dropDownMenu: [
    		{
    			name: 'user',
			    className: 'user',
    			title: 'User details link',
    			open: '<a href="users.php?m=details&amp;id={uid}">{uname}',
    			close: '</a>',
    			attributes: [
    				{
    					type: 'text',
    					name: 'uid',
    					label: 'User #ID'
    				},
            {
    					type: 'text',
    					name: 'uname',
    					label: 'User name'            
            }           
    			]
    		},        
    		{
    			name: 'page',
			    className: 'page',
    			title: 'Page link',
    			open: '<a href="page.php?id={pid}">{ptitle}',
    			close: '</a>',
    			attributes: [
    				{
    					type: 'text',
    					name: 'pid',
    					label: 'Page #ID'
    				},
            {
    					type: 'text',
    					name: 'ptitle',
    					label: 'Page Title'            
            }           
    			]
    		},         
				{
					name: 'f',
					className: 'f',
					title: 'Flag (country code)',
					open: '<a href="users.php?f=country_{countrycode}"><img src="system/img/flags/f-{countrycode}.gif" alt="" />',
					close: '</a>',
					attributes: [
    				{
    					type: 'text',
    					name: 'countrycode',
    					label: 'Country code'
    				}          
    			]
        }, 
				{
					name: 'pfs',
					className: 'pfs',
					title: 'Link to PFS file',
					open: '<a href="datas/users/{file}"><img src="system/img/admin/pfs.png" alt="" />{file}',
					close: '</a>',
					attributes: [
    				{
    					type: 'text',
    					name: 'file',
    					label: 'File name in PFS'
    				}          
    			]
        }, 
				{
					name: 'topic',
					className: 'topic',
					title: 'Forum topic #ID',
					open: '<a href="forums.php?m=posts&amp;q={topic}">Topic #{topic}',
					close: '</a>',
					attributes: [
    				{
    					type: 'text',
    					name: 'topic',
    					label: 'Topic #ID'
    				}          
    			]
        },
				{
					name: 'post',
					className: 'spost',
					title: 'Forum post #ID',
					open: '<a href="forums.php?m=posts&amp;p={post}#$1">Post #{post}',
					close: '</a>',
					attributes: [
    				{
    					type: 'text',
    					name: 'post',
    					label: 'Post #ID'
    				}          
    			]
        },
				{
					name: 'pm',
					className: 'pm',
					title: 'PM to user #ID',
					open: '<a href="pm.php?m=send&amp;to={uid}">PM',
					close: '</a>',
					attributes: [
    				{
    					type: 'text',
    					name: 'uid',
    					label: 'To user #ID'
    				}          
    			]
        }      
      ]
		},    
    Tb3.addons.hsmilies,    
		{separator: true},
		{
			name: 'fonts',
			className: 'font',
			title: 'Fonts',
			dropDownMenu: [
				{
					name: 'big',
					className: 'font',
					title: 'Big',
					open: '<span style="font-size:18pt">',
					close: '</span>'
				},
				{
					name: 'normal',
					className: 'font',
					title: 'Normal',
					open: '<span style="font-size:12pt">',
					close: '</span>'
				},
				{
					name: 'small',
					className: 'font',
					title: 'Small',
					open: '<span style="font-size:9pt">',
					close: '</span>'
				}
			]
		},							 
		{
			separator: true
		},
		{
			name: 'ul',
			title: 'Unordered List',
			open: '<ul>',
			close: "\n</ul>",
			prepend: "\n",
			wrapSelection: "\n   <li>{selection}</li>",
			wrapMultiline: true
		},
		{
			name: 'ol',
			title: 'Ordered List',
			open: '<ol>',
			close: "\n</ol>",
			prepend: "\n",
			wrapSelection: "\n   <li>{selection}</li>",
			wrapMultiline: true
		},
		{
			name: 'li',
			title: 'List Item',
			open: '<li>',
			close: '</li>',
			prepend: "\n   ",
			wrapMultiline: true
		},
		{separator: true},
		{
			name: 'quote',
			className: 'blockquote',
			title: 'Blockquote',
			open: '<blockquote{attributes}>',
			close: '</blockquote>',
			prepend: "\n",
			wrapSelection: "\n{selection}\n",
			attributes: [
				{
					type: 'text',
					name: 'cite',
					label: 'Cite'
				}
			]
		},
		{
			name: 'code',
			className: 'tb3code',
			open: '<code>',
			close: '</code>'
		},
		{
			name: 'more',
			title: 'More separator line',
			open: '<hr id="readmore" />',
			close: ''
		},
		Tb3.addons.searchAndReplace,				
    Tb3.addons.specialChars,
		Tb3.addons.help,    
		Tb3.addons.preview
	]
};