//Load button actions for BBMode

Tb3.conf.bbcode = {
	preview: {
		parser: 'plug.php?ajx=textboxer3&mode=bbcode',
		autoRefresh: false
	},
	markup: [
		{
			name: 'bold',
			className: 'strong',
			title: 'Bold',
			key: 'B',			
			open: '[b]',
			close: '[/b]'
		},
		{
			name: 'italic',
			className: 'italic',
			title: 'Italic',
			key: 'I',			
			open: '[i]',
			close: '[/i]'
		},
		{
			name: 'underline',
			title: 'Underline',
			key: 'U',
			open: '[u]',
			close: '[/u]'
		},
		{
			name: 'del',
			title: 'Strike Through',
			open: '[del]',
			close: '[/del]'
		},
		{separator: true},        
		{
			name: 'h',
			title: 'Heading',
			dropDownMenu: [
      		{
      			name: 'h1',
      			title: 'Heading 1',
      			open: '[h1]',
      			close: '[/h1]'
      		},
      		{
      			name: 'h2',
      			title: 'Heading 2',
      			open: '[h2]',
      			close: '[/h2]'
      		},
      		{
      			name: 'h3',
      			title: 'Heading 3',
      			open: '[h3]',
      			close: '[/h3]'
      		},
      		{
      			name: 'h4',
      			title: 'Heading 4',
      			open: '[h4]',
      			close: '[/h4]'
      		},
      		{
      			name: 'h5',
      			title: 'Heading 5',
      			open: '[h5]',
      			close: '[/h5]'
      		},
      		{
      			name: 'h6',
      			title: 'Heading 6',
      			open: '[h6]',
      			close: '[/h6]'
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
					open: '[p]',
					close: '[/p]'
        }, 
				{
					name: 'hr',
					className: 'hr',
					title: 'Horizontal line',
					open: '[hr]',
					close: ''
        },
				{
					name: 'spacer',
					className: 'spacer',
					title: 'Non breaking space',
					open: '[_]',
					close: ''
        },
    		{
    			name: 'ac',
			    className: 'ac',
    			title: 'Acronym',
    			open: '[ac={acronym}]{explanation}',
    			close: '[/ac]',
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
					open: '[spoiler]',
					close: '[/spoiler]'
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
					open: '[center]',
					close: '[/center]'
				},
				{
					name: 'align-left',
					className: 'align-left',
					title: 'Align Left',
					open: '[left]',
					close: '[/left]'
				},
				{
					name: 'align-right',
					className: 'align-right',
					title: 'Align Right',
					open: '[right]',
					close: '[/right]'
				},
				{
					name: 'align-justify',
					className: 'align-justify',
					title: 'Align Justify',
					open: '[justify]',
					close: '[/justify]'
				}
			]
		},    
		{separator: true},
		{
			name: 'img',
	    className: 'img',
			title: 'Image',
			open: '[img]{iurl}',
			close: '[/img]',
			attributes: [
				{
					type: 'text',
					name: 'iurl',
					label: 'Image URL'
				}
			]
		},
		{
			name: 'thumb',
			title: 'Thumbnail',
			open: '[t={turl}]{burl}',
			close: '[/t]',
			attributes: [
				{
					type: 'text',
					name: 'turl',
					label: 'Thumbnail URL'
				},
				{
					type: 'text',
					name: 'burl',
					label: 'Big Image URL'
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
					open: '[colright]',
					close: '[/colright]'
				},
				{
					name: 'column-left',
					className: 'column-left',
					title: 'Column Left',
					open: '[colleft]',
					close: '[/colleft]'
				}
			]
		},
		{
			name: 'urls',
			className: 'a',
			title: 'Link and Email',
			dropDownMenu: [
    		{
    			name: 'url',
			    className: 'url',
    			title: 'Link',
    			open: '[url={url}]{utitle}',
    			close: '[/url]',
    			attributes: [
    				{
    					type: 'text',
    					name: 'url',
    					label: 'URL'
    				},
            {
    					type: 'text',
    					name: 'utitle',
    					label: 'Text link'            
            }           
    			]
    		},
    		{
    			name: 'email',
			    className: 'email',
    			title: 'E-mail',
    			open: '[email={email}]{etitle}',
    			close: '[/email]',
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
		{
			name: 'colors',
			className: 'colors',      
			title: 'Colorize the text',
			dropDownMenu: [
          Tb3.addons.colorPicker,      
          {
      			name: 'black',
      			title: 'Black pencil',
      			open: '[black]',
      			close: '[/black]'
      		},
      		{
      			name: 'grey',
      			title: 'Grey pencil',
      			open: '[grey]',
      			close: '[/grey]'
      		},
      		{
      			name: 'sea',
      			title: 'Deep blue pencil',
      			open: '[sea]',
      			close: '[/sea]'
      		},
      		{
      			name: 'blue',
      			title: 'Blue pencil',
      			open: '[blue]',
      			close: '[/blue]'
      		},
      		{
      			name: 'sky',
      			title: 'Light blue pencil',
      			open: '[sky]',
      			close: '[/sky]'
      		},
      		{
      			name: 'green',
      			title: 'Green pensil',
      			open: '[green]',
      			close: '[/green]'
      		},
      		{
      			name: 'yellow',
      			title: 'Yellow pensil',
      			open: '[yellow]',
      			close: '[/yellow]'
      		},
      		{
      			name: 'orange',
      			title: 'Orange pencil',
      			open: '[orange]',
      			close: '[/orange]'
      		},
      		{
      			name: 'red',
      			title: 'Red pensil',
      			open: '[red]',
      			close: '[/red]'
      		},
      		{
      			name: 'white',
      			title: 'White pensil',
      			open: '[white]',
      			close: '[/white]'
      		},
      		{
      			name: 'pink',
      			title: 'Pink pensil',
      			open: '[pink]',
      			close: '[/pink]'
      		},
      		{
      			name: 'purple',
      			title: 'Purple pensil',
      			open: '[purple]',
      			close: '[/purple]'
      		}
			]
		},
		{
			name: 'video',
			title: 'Insert Video',
			className: 'video',      
			dropDownMenu: [
    		{
    			name: 'youtube',
			    className: 'youtube',
    			title: 'Youtube video',
    			open: '[youtube={videoid}]',
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
    			open: '[vk={videoid}]',
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
    			open: '[dailymotion={videoid}]',
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
    			open: '[vimeo={videoid}]',
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
    			open: '[metacafe={videoid}]',
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
    			open: '[rutube={videoid}]',
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
    			open: '[user={uid}]{uname}',
    			close: '[/user]',
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
    			open: '[page={pid}]{ptitle}',
    			close: '[/page]',
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
					open: '[f]',
					close: '[/f]'
        }, 
				{
					name: 'pfs',
					className: 'pfs',
					title: 'Link to PFS file',
					open: '[pfs]',
					close: '[/pfs]'
        }, 
				{
					name: 'topic',
					className: 'topic',
					title: 'Forum topic #ID',
					open: '[topic]',
					close: '[/topic]'
        },
				{
					name: 'post',
					className: 'spost',
					title: 'Forum post #ID',
					open: '[post]',
					close: '[/post]'
        },
				{
					name: 'pm',
					className: 'pm',
					title: 'PM to user #ID',
					open: '[pm]',
					close: '[/pm]'
        }      
      ]
		},    
    Tb3.addons.smilies,    
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
					open: '[size=18]',
					close: '[/size]'
				},
				{
					name: 'normal',
					className: 'font',
					title: 'Normal',
					open: '[size=12]',
					close: '[/size]'
				},
				{
					name: 'small',
					className: 'font',
					title: 'Small',
					open: '[size=9]',
					close: '[/size]'
				}
			]
		},
		{separator: true},
		{
			name: 'ul',
			title: 'Unordered List',
			open: '[list]',
			close: '[/list]',
			prepend: "\n",
			append: "\n",
			wrapSelection: "\n   [*]{selection}\n"
		},
		{
			name: 'ol',
			title: 'Ordered List',
			open: '[list={startingnumber}]',
			close: '[/list]',
			prepend: "\n",
			append: "\n",
			wrapSelection: "\n   [*]{selection}\n",
			attributes: [
				{
					type: 'text',
					name: 'startingnumber',
					label: 'Starting Number'
				}
			]
		},
		{
			name: 'li',
			title: 'List Item',
			open: '[*]',
			prepend: "\n   "
		},
		{
			separator: true	
		},
		{
			name: 'quote',
			className: 'blockquote',
			open: '[quote]',
			close: '[/quote]'
		},
		{
			name: 'code',
			className: 'tb3code',
			open: '[code]',
			close: '[/code]'
		},
		{
			name: 'more',
			title: 'More separator line',
			open: '[more]',
			close: ''
		},
		Tb3.addons.searchAndReplace,
    Tb3.addons.specialChars,
		Tb3.addons.help,		
    Tb3.addons.preview
	]
};