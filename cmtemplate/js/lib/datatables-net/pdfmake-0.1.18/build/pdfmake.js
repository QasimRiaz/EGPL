/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};

/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {

/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId])
/******/ 			return installedModules[moduleId].exports;

/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			exports: {},
/******/ 			id: moduleId,
/******/ 			loaded: false
/******/ 		};

/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);

/******/ 		// Flag the module as loaded
/******/ 		module.loaded = true;

/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}


/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;

/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;

/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";

/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ function(module, exports, __webpack_require__) {

	/* WEBPACK VAR INJECTION */(function(global) {module.exports = global["pdfMake"] = __webpack_require__(1);
	/* WEBPACK VAR INJECTION */}.call(exports, (function() { return this; }())))

/***/ },
/* 1 */
/***/ function(module, exports, __webpack_require__) {

	/* WEBPACK VAR INJECTION */(function(Buffer) {/* jslint node: true */
	/* jslint browser: true */
	/* global BlobBuilder */
	'use strict';

	var PdfPrinter = __webpack_require__(2);
	var saveAs = __webpack_require__(3);

	var defaultClientFonts = {
		Roboto: {
			normal: 'Roboto-Regular.ttf',
			bold: 'Roboto-Medium.ttf',
			italics: 'Roboto-Italic.ttf',
			bolditalics: 'Roboto-Italic.ttf'
		}
	};

	function Document(docDefinition, fonts, vfs) {
		this.docDefinition = docDefinition;
		this.fonts = fonts || defaultClientFonts;
		this.vfs = vfs;
	}

	Document.prototype._createDoc = function(options, callback) {
		var printer = new PdfPrinter(this.fonts);
		printer.fs.bindFS(this.vfs);

		var doc = printer.createPdfKitDocument(this.docDefinition, options);
		var chunks = [];
		var result;

		doc.on('data', function(chunk) {
			chunks.push(chunk);
		});
		doc.on('end', function() {
			result = Buffer.concat(chunks);
			callback(result, doc._pdfMakePages);
		});
		doc.end();
	};

	Document.prototype._getPages = function(options, cb){
	  if (!cb) throw 'getBuffer is an async method and needs a callback argument';
	  this._createDoc(options, function(ignoreBuffer, pages){
	    cb(pages);
	  });
	};

	Document.prototype.open = function(message) {
		// we have to open the window immediately and store the reference
		// otherwise popup blockers will stop us
		var win = window.open('', '_blank');

		try {
			this.getDataUrl(function(result) {
				win.location.href = result;
			});
		} catch(e) {
			win.close();
			throw e;
		}
	};


	Document.prototype.print = function() {
	  this.getDataUrl(function(dataUrl) {
	    var iFrame = document.createElement('iframe');
	    iFrame.style.position = 'absolute';
	    iFrame.style.left = '-99999px';
	    iFrame.src = dataUrl;
	    iFrame.onload = function() {
	      function removeIFrame(){
	        document.body.removeChild(iFrame);
	        document.removeEventListener('click', removeIFrame);
	      }
	      document.addEventListener('click', removeIFrame, false);
	    };

	    document.body.appendChild(iFrame);
	  }, { autoPrint: true });
	};

	Document.prototype.download = function(defaultFileName, cb) {
	   if(typeof defaultFileName === "function") {
	      cb = defaultFileName;
	      defaultFileName = null;
	   }

	   defaultFileName = defaultFileName || 'file.pdf';
	   this.getBuffer(function(result) {
	       saveAs(new Blob([result], {type: 'application/pdf'}), defaultFileName);
	       if (typeof cb === "function") {
	           cb();
	       }
	   });
	};

	Document.prototype.getBase64 = function(cb, options) {
		if (!cb) throw 'getBase64 is an async method and needs a callback argument';
		this._createDoc(options, function(buffer) {
			cb(buffer.toString('base64'));
		});
	};

	Document.prototype.getDataUrl = function(cb, options) {
		if (!cb) throw 'getDataUrl is an async method and needs a callback argument';
		this._createDoc(options, function(buffer) {
			cb('data:application/pdf;base64,' + buffer.toString('base64'));
		});
	};

	Document.prototype.getBuffer = function(cb, options) {
		if (!cb) throw 'getBuffer is an async method and needs a callback argument';
		this._createDoc(options, function(buffer){
	    cb(buffer);
	  });
	};

	module.exports = {
		createPdf: function(docDefinition) {
			return new Document(docDefinition, window.pdfMake.fonts, window.pdfMake.vfs);
		}
	};

	/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(4).Buffer))

/***/ },
/* 2 */
/***/ function(module, exports, __webpack_require__) {

	/* jslint node: true */
	/* global window */
	'use strict';

	var _ = __webpack_require__(11);
	var FontProvider = __webpack_require__(5);
	var LayoutBuilder = __webpack_require__(6);
	var PdfKit = __webpack_require__(28);
	var PDFReference = __webpack_require__(12);
	var sizes = __webpack_require__(7);
	var ImageMeasure = __webpack_require__(8);
	var textDecorator = __webpack_require__(9);
	var FontProvider = __webpack_require__(5);

	////////////////////////////////////////
	// PdfPrinter

	/**
	 * @class Creates an instance of a PdfPrinter which turns document definition into a pdf
	 *
	 * @param {Object} fontDescriptors font definition dictionary
	 *
	 * @example
	 * var fontDescriptors = {
	 *	Roboto: {
	 *		normal: 'fonts/Roboto-Regular.ttf',
	 *		bold: 'fonts/Roboto-Medium.ttf',
	 *		italics: 'fonts/Roboto-Italic.ttf',
	 *		bolditalics: 'fonts/Roboto-Italic.ttf'
	 *	}
	 * };
	 *
	 * var printer = new PdfPrinter(fontDescriptors);
	 */
	function PdfPrinter(fontDescriptors) {
		this.fontDescriptors = fontDescriptors;
	}

	/**
	 * Executes layout engine for the specified document and renders it into a pdfkit document
	 * ready to be saved.
	 *
	 * @param {Object} docDefinition document definition
	 * @param {Object} docDefinition.content an array describing the pdf structure (for more information take a look at the examples in the /examples folder)
	 * @param {Object} [docDefinition.defaultStyle] default (implicit) style definition
	 * @param {Object} [docDefinition.styles] dictionary defining all styles which can be used in the document
	 * @param {Object} [docDefinition.pageSize] page size (pdfkit units, A4 dimensions by default)
	 * @param {Number} docDefinition.pageSize.width width
	 * @param {Number} docDefinition.pageSize.height height
	 * @param {Object} [docDefinition.pageMargins] page margins (pdfkit units)
	 *
	 * @example
	 *
	 * var docDefinition = {
	 *	content: [
	 *		'First paragraph',
	 *		'Second paragraph, this time a little bit longer',
	 *		{ text: 'Third paragraph, slightly bigger font size', fontSize: 20 },
	 *		{ text: 'Another paragraph using a named style', style: 'header' },
	 *		{ text: ['playing with ', 'inlines' ] },
	 *		{ text: ['and ', { text: 'restyling ', bold: true }, 'them'] },
	 *	],
	 *	styles: {
	 *		header: { fontSize: 30, bold: true }
	 *	}
	 * }
	 *
	 * var pdfDoc = printer.createPdfKitDocument(docDefinition);
	 *
	 * pdfDoc.pipe(fs.createWriteStream('sample.pdf'));
	 * pdfDoc.end();
	 *
	 * @return {Object} a pdfKit document object which can be saved or encode to data-url
	 */
	PdfPrinter.prototype.createPdfKitDocument = function(docDefinition, options) {
		options = options || {};

		var pageSize = pageSize2widthAndHeight(docDefinition.pageSize || 'a4');

	  if(docDefinition.pageOrientation === 'landscape') {
	    pageSize = { width: pageSize.height, height: pageSize.width};
	  }
		pageSize.orientation = docDefinition.pageOrientation === 'landscape' ? docDefinition.pageOrientation : 'portrait';

		this.pdfKitDoc = new PdfKit({ size: [ pageSize.width, pageSize.height ], compress: false});
		this.pdfKitDoc.info.Producer = 'pdfmake';
		this.pdfKitDoc.info.Creator = 'pdfmake';
		this.fontProvider = new FontProvider(this.fontDescriptors, this.pdfKitDoc);

	  docDefinition.images = docDefinition.images || {};

		var builder = new LayoutBuilder(
			pageSize,
			fixPageMargins(docDefinition.pageMargins || 40),
	        new ImageMeasure(this.pdfKitDoc, docDefinition.images));

	  registerDefaultTableLayouts(builder);
	  if (options.tableLayouts) {
	    builder.registerTableLayouts(options.tableLayouts);
	  }

		var pages = builder.layoutDocument(docDefinition.content, this.fontProvider, docDefinition.styles || {}, docDefinition.defaultStyle || { fontSize: 12, font: 'Roboto' }, docDefinition.background, docDefinition.header, docDefinition.footer, docDefinition.images, docDefinition.watermark, docDefinition.pageBreakBefore);

		renderPages(pages, this.fontProvider, this.pdfKitDoc);

		if(options.autoPrint){
	        var jsRef = this.pdfKitDoc.ref({
				S: 'JavaScript',
				JS: new StringObject('this.print\\(true\\);')
			});
			var namesRef = this.pdfKitDoc.ref({
				Names: [new StringObject('EmbeddedJS'), new PDFReference(this.pdfKitDoc, jsRef.id)],
			});

			jsRef.end();
			namesRef.end();

			this.pdfKitDoc._root.data.Names = {
				JavaScript: new PDFReference(this.pdfKitDoc, namesRef.id)
			};
		}
		return this.pdfKitDoc;
	};

	function fixPageMargins(margin) {
	    if (!margin) return null;

	    if (typeof margin === 'number' || margin instanceof Number) {
	        margin = { left: margin, right: margin, top: margin, bottom: margin };
	    } else if (margin instanceof Array) {
	        if (margin.length === 2) {
	            margin = { left: margin[0], top: margin[1], right: margin[0], bottom: margin[1] };
	        } else if (margin.length === 4) {
	            margin = { left: margin[0], top: margin[1], right: margin[2], bottom: margin[3] };
	        } else throw 'Invalid pageMargins definition';
	    }

	    return margin;
	}

	function registerDefaultTableLayouts(layoutBuilder) {
	  layoutBuilder.registerTableLayouts({
	    noBorders: {
	      hLineWidth: function(i) { return 0; },
	      vLineWidth: function(i) { return 0; },
	      paddingLeft: function(i) { return i && 4 || 0; },
	      paddingRight: function(i, node) { return (i < node.table.widths.length - 1) ? 4 : 0; },
	    },
	    headerLineOnly: {
	      hLineWidth: function(i, node) {
	        if (i === 0 || i === node.table.body.length) return 0;
	        return (i === node.table.headerRows) ? 2 : 0;
	      },
	      vLineWidth: function(i) { return 0; },
	      paddingLeft: function(i) {
	        return i === 0 ? 0 : 8;
	      },
	      paddingRight: function(i, node) {
	        return (i === node.table.widths.length - 1) ? 0 : 8;
	      }
	    },
	    lightHorizontalLines: {
	      hLineWidth: function(i, node) {
	        if (i === 0 || i === node.table.body.length) return 0;
	        return (i === node.table.headerRows) ? 2 : 1;
	      },
	      vLineWidth: function(i) { return 0; },
	      hLineColor: function(i) { return i === 1 ? 'black' : '#aaa'; },
	      paddingLeft: function(i) {
	        return i === 0 ? 0 : 8;
	      },
	      paddingRight: function(i, node) {
	        return (i === node.table.widths.length - 1) ? 0 : 8;
	      }
	    }
	  });
	}

	var defaultLayout = {
	  hLineWidth: function(i, node) { return 1; }, //return node.table.headerRows && i === node.table.headerRows && 3 || 0; },
	  vLineWidth: function(i, node) { return 1; },
	  hLineColor: function(i, node) { return 'black'; },
	  vLineColor: function(i, node) { return 'black'; },
	  paddingLeft: function(i, node) { return 4; }, //i && 4 || 0; },
	  paddingRight: function(i, node) { return 4; }, //(i < node.table.widths.length - 1) ? 4 : 0; },
	  paddingTop: function(i, node) { return 2; },
	  paddingBottom: function(i, node) { return 2; }
	};

	function pageSize2widthAndHeight(pageSize) {
	    if (typeof pageSize == 'string' || pageSize instanceof String) {
	        var size = sizes[pageSize.toUpperCase()];
	        if (!size) throw ('Page size ' + pageSize + ' not recognized');
	        return { width: size[0], height: size[1] };
	    }

	    return pageSize;
	}

	function StringObject(str){
		this.isString = true;
		this.toString = function(){
			return str;
		};
	}

	function updatePageOrientationInOptions(currentPage, pdfKitDoc) {
		var previousPageOrientation = pdfKitDoc.options.size[0] > pdfKitDoc.options.size[1] ? 'landscape' : 'portrait';

		if(currentPage.pageSize.orientation !== previousPageOrientation) {
			var width = pdfKitDoc.options.size[0];
			var height = pdfKitDoc.options.size[1];
			pdfKitDoc.options.size = [height, width];
		}
	}

	function renderPages(pages, fontProvider, pdfKitDoc) {
	  pdfKitDoc._pdfMakePages = pages;
		for (var i = 0; i < pages.length; i++) {
			if (i > 0) {
				updatePageOrientationInOptions(pages[i], pdfKitDoc);
				pdfKitDoc.addPage(pdfKitDoc.options);
			}

			var page = pages[i];
	    for(var ii = 0, il = page.items.length; ii < il; ii++) {
	        var item = page.items[ii];
	        switch(item.type) {
	          case 'vector':
	              renderVector(item.item, pdfKitDoc);
	              break;
	          case 'line':
	              renderLine(item.item, item.item.x, item.item.y, pdfKitDoc);
	              break;
	          case 'image':
	              renderImage(item.item, item.item.x, item.item.y, pdfKitDoc);
	              break;
					}
	    }
	    if(page.watermark){
	      renderWatermark(page, pdfKitDoc);
		}

	    fontProvider.setFontRefsToPdfDoc();
	  }
	}

	function renderLine(line, x, y, pdfKitDoc) {
		x = x || 0;
		y = y || 0;

		var ascenderHeight = line.getAscenderHeight();

		textDecorator.drawBackground(line, x, y, pdfKitDoc);

		//TODO: line.optimizeInlines();
		for(var i = 0, l = line.inlines.length; i < l; i++) {
			var inline = line.inlines[i];

			pdfKitDoc.fill(inline.color || 'black');

			pdfKitDoc.save();
			pdfKitDoc.transform(1, 0, 0, -1, 0, pdfKitDoc.page.height);


	    var encoded = inline.font.encode(inline.text);
			pdfKitDoc.addContent('BT');

			pdfKitDoc.addContent('' + (x + inline.x) + ' ' + (pdfKitDoc.page.height - y - ascenderHeight) + ' Td');
			pdfKitDoc.addContent('/' + encoded.fontId + ' ' + inline.fontSize + ' Tf');

	        pdfKitDoc.addContent('<' + encoded.encodedText + '> Tj');

			pdfKitDoc.addContent('ET');
			pdfKitDoc.restore();
		}

		textDecorator.drawDecorations(line, x, y, pdfKitDoc);

	}

	function renderWatermark(page, pdfKitDoc){
		var watermark = page.watermark;

		pdfKitDoc.fill('black');
		pdfKitDoc.opacity(0.6);

		pdfKitDoc.save();
		pdfKitDoc.transform(1, 0, 0, -1, 0, pdfKitDoc.page.height);

		var angle = Math.atan2(pdfKitDoc.page.height, pdfKitDoc.page.width) * 180/Math.PI;
		pdfKitDoc.rotate(angle, {origin: [pdfKitDoc.page.width/2, pdfKitDoc.page.height/2]});

	  var encoded = watermark.font.encode(watermark.text);
		pdfKitDoc.addContent('BT');
		pdfKitDoc.addContent('' + (pdfKitDoc.page.width/2 - watermark.size.size.width/2) + ' ' + (pdfKitDoc.page.height/2 - watermark.size.size.height/4) + ' Td');
		pdfKitDoc.addContent('/' + encoded.fontId + ' ' + watermark.size.fontSize + ' Tf');
		pdfKitDoc.addContent('<' + encoded.encodedText + '> Tj');
		pdfKitDoc.addContent('ET');
		pdfKitDoc.restore();
	}

	function renderVector(vector, pdfDoc) {
		//TODO: pdf optimization (there's no need to write all properties everytime)
		pdfDoc.lineWidth(vector.lineWidth || 1);
		if (vector.dash) {
			pdfDoc.dash(vector.dash.length, { space: vector.dash.space || vector.dash.length });
		} else {
			pdfDoc.undash();
		}
		pdfDoc.fillOpacity(vector.fillOpacity || 1);
		pdfDoc.strokeOpacity(vector.strokeOpacity || 1);
		pdfDoc.lineJoin(vector.lineJoin || 'miter');

		//TODO: clipping

		switch(vector.type) {
			case 'ellipse':
				pdfDoc.ellipse(vector.x, vector.y, vector.r1, vector.r2);
				break;
			case 'rect':
				if (vector.r) {
					pdfDoc.roundedRect(vector.x, vector.y, vector.w, vector.h, vector.r);
				} else {
					pdfDoc.rect(vector.x, vector.y, vector.w, vector.h);
				}
				break;
			case 'line':
				pdfDoc.moveTo(vector.x1, vector.y1);
				pdfDoc.lineTo(vector.x2, vector.y2);
				break;
			case 'polyline':
				if (vector.points.length === 0) break;

				pdfDoc.moveTo(vector.points[0].x, vector.points[0].y);
				for(var i = 1, l = vector.points.length; i < l; i++) {
					pdfDoc.lineTo(vector.points[i].x, vector.points[i].y);
				}

				if (vector.points.length > 1) {
					var p1 = vector.points[0];
					var pn = vector.points[vector.points.length - 1];

					if (vector.closePath || p1.x === pn.x && p1.y === pn.y) {
						pdfDoc.closePath();
					}
				}
				break;
		}

		if (vector.color && vector.lineColor) {
			pdfDoc.fillAndStroke(vector.color, vector.lineColor);
		} else if (vector.color) {
			pdfDoc.fill(vector.color);
		} else {
			pdfDoc.stroke(vector.lineColor || 'black');
		}
	}

	function renderImage(image, x, y, pdfKitDoc) {
	    pdfKitDoc.image(image.image, image.x, image.y, { width: image._width, height: image._height });
	}

	module.exports = PdfPrinter;


	/* temporary browser extension */
	PdfPrinter.prototype.fs = __webpack_require__(10);


/***/ },
/* 3 */
/***/ function(module, exports, __webpack_require__) {

	var __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;/* WEBPACK VAR INJECTION */(function(module) {/* FileSaver.js
	 * A saveAs() FileSaver implementation.
	 * 2014-08-29
	 *
	 * By Eli Grey, http://eligrey.com
	 * License: X11/MIT
	 *   See https://github.com/eligrey/FileSaver.js/blob/master/LICENSE.md
	 */

	/*global self */
	/*jslint bitwise: true, indent: 4, laxbreak: true, laxcomma: true, smarttabs: true, plusplus: true */

	/*! @source http://purl.eligrey.com/github/FileSaver.js/blob/master/FileSaver.js */

	var saveAs = saveAs
	  // IE 10+ (native saveAs)
	  || (typeof navigator !== "undefined" &&
	      navigator.msSaveOrOpenBlob && navigator.msSaveOrOpenBlob.bind(navigator))
	  // Everyone else
	  || (function(view) {
		"use strict";
		// IE <10 is explicitly unsupported
		if (typeof navigator !== "undefined" &&
		    /MSIE [1-9]\./.test(navigator.userAgent)) {
			return;
		}
		var
			  doc = view.document
			  // only get URL when necessary in case Blob.js hasn't overridden it yet
			, get_URL = function() {
				return view.URL || view.webkitURL || view;
			}
			, save_link = doc.createElementNS("http://www.w3.org/1999/xhtml", "a")
			, can_use_save_link = "download" in save_link
			, click = function(node) {
				var event = doc.createEvent("MouseEvents");
				event.initMouseEvent(
					"click", true, false, view, 0, 0, 0, 0, 0
					, false, false, false, false, 0, null
				);
				node.dispatchEvent(event);
			}
			, webkit_req_fs = view.webkitRequestFileSystem
			, req_fs = view.requestFileSystem || webkit_req_fs || view.mozRequestFileSystem
			, throw_outside = function(ex) {
				(view.setImmediate || view.setTimeout)(function() {
					throw ex;
				}, 0);
			}
			, force_saveable_type = "application/octet-stream"
			, fs_min_size = 0
			// See https://code.google.com/p/chromium/issues/detail?id=375297#c7 for
			// the reasoning behind the timeout and revocation flow
			, arbitrary_revoke_timeout = 10
			, revoke = function(file) {
				var revoker = function() {
					if (typeof file === "string") { // file is an object URL
						get_URL().revokeObjectURL(file);
					} else { // file is a File
						file.remove();
					}
				};
				if (view.chrome) {
					revoker();
				} else {
					setTimeout(revoker, arbitrary_revoke_timeout);
				}
			}
			, dispatch = function(filesaver, event_types, event) {
				event_types = [].concat(event_types);
				var i = event_types.length;
				while (i--) {
					var listener = filesaver["on" + event_types[i]];
					if (typeof listener === "function") {
						try {
							listener.call(filesaver, event || filesaver);
						} catch (ex) {
							throw_outside(ex);
						}
					}
				}
			}
			, FileSaver = function(blob, name) {
				// First try a.download, then web filesystem, then object URLs
				var
					  filesaver = this
					, type = blob.type
					, blob_changed = false
					, object_url
					, target_view
					, dispatch_all = function() {
						dispatch(filesaver, "writestart progress write writeend".split(" "));
					}
					// on any filesys errors revert to saving with object URLs
					, fs_error = function() {
						// don't create more object URLs than needed
						if (blob_changed || !object_url) {
							object_url = get_URL().createObjectURL(blob);
						}
						if (target_view) {
							target_view.location.href = object_url;
						} else {
							var new_tab = view.open(object_url, "_blank");
							if (new_tab == undefined && typeof safari !== "undefined") {
								//Apple do not allow window.open, see http://bit.ly/1kZffRI
								view.location.href = object_url
							}
						}
						filesaver.readyState = filesaver.DONE;
						dispatch_all();
						revoke(object_url);
					}
					, abortable = function(func) {
						return function() {
							if (filesaver.readyState !== filesaver.DONE) {
								return func.apply(this, arguments);
							}
						};
					}
					, create_if_not_found = {create: true, exclusive: false}
					, slice
				;
				filesaver.readyState = filesaver.INIT;
				if (!name) {
					name = "download";
				}
				if (can_use_save_link) {
					object_url = get_URL().createObjectURL(blob);
					save_link.href = object_url;
					save_link.download = name;
					click(save_link);
					filesaver.readyState = filesaver.DONE;
					dispatch_all();
					revoke(object_url);
					return;
				}
				// Object and web filesystem URLs have a problem saving in Google Chrome when
				// viewed in a tab, so I force save with application/octet-stream
				// http://code.google.com/p/chromium/issues/detail?id=91158
				// Update: Google errantly closed 91158, I submitted it again:
				// https://code.google.com/p/chromium/issues/detail?id=389642
				if (view.chrome && type && type !== force_saveable_type) {
					slice = blob.slice || blob.webkitSlice;
					blob = slice.call(blob, 0, blob.size, force_saveable_type);
					blob_changed = true;
				}
				// Since I can't be sure that the guessed media type will trigger a download
				// in WebKit, I append .download to the filename.
				// https://bugs.webkit.org/show_bug.cgi?id=65440
				if (webkit_req_fs && name !== "download") {
					name += ".download";
				}
				if (type === force_saveable_type || webkit_req_fs) {
					target_view = view;
				}
				if (!req_fs) {
					fs_error();
					return;
				}
				fs_min_size += blob.size;
				req_fs(view.TEMPORARY, fs_min_size, abortable(function(fs) {
					fs.root.getDirectory("saved", create_if_not_found, abortable(function(dir) {
						var save = function() {
							dir.getFile(name, create_if_not_found, abortable(function(file) {
								file.createWriter(abortable(function(writer) {
									writer.onwriteend = function(event) {
										target_view.location.href = file.toURL();
										filesaver.readyState = filesaver.DONE;
										dispatch(filesaver, "writeend", event);
										revoke(file);
									};
									writer.onerror = function() {
										var error = writer.error;
										if (error.code !== error.ABORT_ERR) {
											fs_error();
										}
									};
									"writestart progress write abort".split(" ").forEach(function(event) {
										writer["on" + event] = filesaver["on" + event];
									});
									writer.write(blob);
									filesaver.abort = function() {
										writer.abort();
										filesaver.readyState = filesaver.DONE;
									};
									filesaver.readyState = filesaver.WRITING;
								}), fs_error);
							}), fs_error);
						};
						dir.getFile(name, {create: false}, abortable(function(file) {
							// delete file if it already exists
							file.remove();
							save();
						}), abortable(function(ex) {
							if (ex.code === ex.NOT_FOUND_ERR) {
								save();
							} else {
								fs_error();
							}
						}));
					}), fs_error);
				}), fs_error);
			}
			, FS_proto = FileSaver.prototype
			, saveAs = function(blob, name) {
				return new FileSaver(blob, name);
			}
		;
		FS_proto.abort = function() {
			var filesaver = this;
			filesaver.readyState = filesaver.DONE;
			dispatch(filesaver, "abort");
		};
		FS_proto.readyState = FS_proto.INIT = 0;
		FS_proto.WRITING = 1;
		FS_proto.DONE = 2;

		FS_proto.error =
		FS_proto.onwritestart =
		FS_proto.onprogress =
		FS_proto.onwrite =
		FS_proto.onabort =
		FS_proto.onerror =
		FS_proto.onwriteend =
			null;

		return saveAs;
	}(
		   typeof self !== "undefined" && self
		|| typeof window !== "undefined" && window
		|| this.content
	));
	// `self` is undefined in Firefox for Android content script context
	// while `this` is nsIContentFrameMessageManager
	// with an attribute `content` that corresponds to the window

	if (typeof module !== "undefined" && module !== null) {
	  module.exports = saveAs;
	} else if (("function" !== "undefined" && __webpack_require__(13) !== null) && (__webpack_require__(14) != null)) {
	  !(__WEBPACK_AMD_DEFINE_ARRAY__ = [], __WEBPACK_AMD_DEFINE_RESULT__ = function() {
	    return saveAs;
	  }.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__), __WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
	}

	/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(15)(module)))

/***/ },
/* 4 */
/***/ function(module, exports, __webpack_require__) {

	/* WEBPACK VAR INJECTION */(function(Buffer) {/*!
	 * The buffer module from node.js, for the browser.
	 *
	 * @author   Feross Aboukhadijeh <feross@feross.org> <http://feross.org>
	 * @license  MIT
	 */

	var base64 = __webpack_require__(31)
	var ieee754 = __webpack_require__(29)
	var isArray = __webpack_require__(30)

	exports.Buffer = Buffer
	exports.SlowBuffer = SlowBuffer
	exports.INSPECT_MAX_BYTES = 50
	Buffer.poolSize = 8192 // not used by this implementation

	var kMaxLength = 0x3fffffff
	var rootParent = {}

	/**
	 * If `Buffer.TYPED_ARRAY_SUPPORT`:
	 *   === true    Use Uint8Array implementation (fastest)
	 *   === false   Use Object implementation (most compatible, even IE6)
	 *
	 * Browsers that support typed arrays are IE 10+, Firefox 4+, Chrome 7+, Safari 5.1+,
	 * Opera 11.6+, iOS 4.2+.
	 *
	 * Note:
	 *
	 * - Implementation must support adding new properties to `Uint8Array` instances.
	 *   Firefox 4-29 lacked support, fixed in Firefox 30+.
	 *   See: https://bugzilla.mozilla.org/show_bug.cgi?id=695438.
	 *
	 *  - Chrome 9-10 is missing the `TypedArray.prototype.subarray` function.
	 *
	 *  - IE10 has a broken `TypedArray.prototype.subarray` function which returns arrays of
	 *    incorrect length in some situations.
	 *
	 * We detect these buggy browsers and set `Buffer.TYPED_ARRAY_SUPPORT` to `false` so they will
	 * get the Object implementation, which is slower but will work correctly.
	 */
	Buffer.TYPED_ARRAY_SUPPORT = (function () {
	  try {
	    var buf = new ArrayBuffer(0)
	    var arr = new Uint8Array(buf)
	    arr.foo = function () { return 42 }
	    return arr.foo() === 42 && // typed array instances can be augmented
	        typeof arr.subarray === 'function' && // chrome 9-10 lack `subarray`
	        new Uint8Array(1).subarray(1, 1).byteLength === 0 // ie10 has broken `subarray`
	  } catch (e) {
	    return false
	  }
	})()

	/**
	 * Class: Buffer
	 * =============
	 *
	 * The Buffer constructor returns instances of `Uint8Array` that are augmented
	 * with function properties for all the node `Buffer` API functions. We use
	 * `Uint8Array` so that square bracket notation works as expected -- it returns
	 * a single octet.
	 *
	 * By augmenting the instances, we can avoid modifying the `Uint8Array`
	 * prototype.
	 */
	function Buffer (arg) {
	  if (!(this instanceof Buffer)) {
	    // Avoid going through an ArgumentsAdaptorTrampoline in the common case.
	    if (arguments.length > 1) return new Buffer(arg, arguments[1])
	    return new Buffer(arg)
	  }

	  this.length = 0
	  this.parent = undefined

	  // Common case.
	  if (typeof arg === 'number') {
	    return fromNumber(this, arg)
	  }

	  // Slightly less common case.
	  if (typeof arg === 'string') {
	    return fromString(this, arg, arguments.length > 1 ? arguments[1] : 'utf8')
	  }

	  // Unusual.
	  return fromObject(this, arg)
	}

	function fromNumber (that, length) {
	  that = allocate(that, length < 0 ? 0 : checked(length) | 0)
	  if (!Buffer.TYPED_ARRAY_SUPPORT) {
	    for (var i = 0; i < length; i++) {
	      that[i] = 0
	    }
	  }
	  return that
	}

	function fromString (that, string, encoding) {
	  if (typeof encoding !== 'string' || encoding === '') encoding = 'utf8'

	  // Assumption: byteLength() return value is always < kMaxLength.
	  var length = byteLength(string, encoding) | 0
	  that = allocate(that, length)

	  that.write(string, encoding)
	  return that
	}

	function fromObject (that, object) {
	  if (Buffer.isBuffer(object)) return fromBuffer(that, object)

	  if (isArray(object)) return fromArray(that, object)

	  if (object == null) {
	    throw new TypeError('must start with number, buffer, array or string')
	  }

	  if (typeof ArrayBuffer !== 'undefined' && object.buffer instanceof ArrayBuffer) {
	    return fromTypedArray(that, object)
	  }

	  if (object.length) return fromArrayLike(that, object)

	  return fromJsonObject(that, object)
	}

	function fromBuffer (that, buffer) {
	  var length = checked(buffer.length) | 0
	  that = allocate(that, length)
	  buffer.copy(that, 0, 0, length)
	  return that
	}

	function fromArray (that, array) {
	  var length = checked(array.length) | 0
	  that = allocate(that, length)
	  for (var i = 0; i < length; i += 1) {
	    that[i] = array[i] & 255
	  }
	  return that
	}

	// Duplicate of fromArray() to keep fromArray() monomorphic.
	function fromTypedArray (that, array) {
	  var length = checked(array.length) | 0
	  that = allocate(that, length)
	  // Truncating the elements is probably not what people expect from typed
	  // arrays with BYTES_PER_ELEMENT > 1 but it's compatible with the behavior
	  // of the old Buffer constructor.
	  for (var i = 0; i < length; i += 1) {
	    that[i] = array[i] & 255
	  }
	  return that
	}

	function fromArrayLike (that, array) {
	  var length = checked(array.length) | 0
	  that = allocate(that, length)
	  for (var i = 0; i < length; i += 1) {
	    that[i] = array[i] & 255
	  }
	  return that
	}

	// Deserialize { type: 'Buffer', data: [1,2,3,...] } into a Buffer object.
	// Returns a zero-length buffer for inputs that don't conform to the spec.
	function fromJsonObject (that, object) {
	  var array
	  var length = 0

	  if (object.type === 'Buffer' && isArray(object.data)) {
	    array = object.data
	    length = checked(array.length) | 0
	  }
	  that = allocate(that, length)

	  for (var i = 0; i < length; i += 1) {
	    that[i] = array[i] & 255
	  }
	  return that
	}

	function allocate (that, length) {
	  if (Buffer.TYPED_ARRAY_SUPPORT) {
	    // Return an augmented `Uint8Array` instance, for best performance
	    that = Buffer._augment(new Uint8Array(length))
	  } else {
	    // Fallback: Return an object instance of the Buffer class
	    that.length = length
	    that._isBuffer = true
	  }

	  var fromPool = length !== 0 && length <= Buffer.poolSize >>> 1
	  if (fromPool) that.parent = rootParent

	  return that
	}

	function checked (length) {
	  // Note: cannot use `length < kMaxLength` here because that fails when
	  // length is NaN (which is otherwise coerced to zero.)
	  if (length >= kMaxLength) {
	    throw new RangeError('Attempt to allocate Buffer larger than maximum ' +
	                         'size: 0x' + kMaxLength.toString(16) + ' bytes')
	  }
	  return length | 0
	}

	function SlowBuffer (subject, encoding) {
	  if (!(this instanceof SlowBuffer)) return new SlowBuffer(subject, encoding)

	  var buf = new Buffer(subject, encoding)
	  delete buf.parent
	  return buf
	}

	Buffer.isBuffer = function isBuffer (b) {
	  return !!(b != null && b._isBuffer)
	}

	Buffer.compare = function compare (a, b) {
	  if (!Buffer.isBuffer(a) || !Buffer.isBuffer(b)) {
	    throw new TypeError('Arguments must be Buffers')
	  }

	  if (a === b) return 0

	  var x = a.length
	  var y = b.length

	  var i = 0
	  var len = Math.min(x, y)
	  while (i < len) {
	    if (a[i] !== b[i]) break

	    ++i
	  }

	  if (i !== len) {
	    x = a[i]
	    y = b[i]
	  }

	  if (x < y) return -1
	  if (y < x) return 1
	  return 0
	}

	Buffer.isEncoding = function isEncoding (encoding) {
	  switch (String(encoding).toLowerCase()) {
	    case 'hex':
	    case 'utf8':
	    case 'utf-8':
	    case 'ascii':
	    case 'binary':
	    case 'base64':
	    case 'raw':
	    case 'ucs2':
	    case 'ucs-2':
	    case 'utf16le':
	    case 'utf-16le':
	      return true
	    default:
	      return false
	  }
	}

	Buffer.concat = function concat (list, length) {
	  if (!isArray(list)) throw new TypeError('list argument must be an Array of Buffers.')

	  if (list.length === 0) {
	    return new Buffer(0)
	  } else if (list.length === 1) {
	    return list[0]
	  }

	  var i
	  if (length === undefined) {
	    length = 0
	    for (i = 0; i < list.length; i++) {
	      length += list[i].length
	    }
	  }

	  var buf = new Buffer(length)
	  var pos = 0
	  for (i = 0; i < list.length; i++) {
	    var item = list[i]
	    item.copy(buf, pos)
	    pos += item.length
	  }
	  return buf
	}

	function byteLength (string, encoding) {
	  if (typeof string !== 'string') string = String(string)

	  if (string.length === 0) return 0

	  switch (encoding || 'utf8') {
	    case 'ascii':
	    case 'binary':
	    case 'raw':
	      return string.length
	    case 'ucs2':
	    case 'ucs-2':
	    case 'utf16le':
	    case 'utf-16le':
	      return string.length * 2
	    case 'hex':
	      return string.length >>> 1
	    case 'utf8':
	    case 'utf-8':
	      return utf8ToBytes(string).length
	    case 'base64':
	      return base64ToBytes(string).length
	    default:
	      return string.length
	  }
	}
	Buffer.byteLength = byteLength

	// pre-set for values that may exist in the future
	Buffer.prototype.length = undefined
	Buffer.prototype.parent = undefined

	// toString(encoding, start=0, end=buffer.length)
	Buffer.prototype.toString = function toString (encoding, start, end) {
	  var loweredCase = false

	  start = start | 0
	  end = end === undefined || end === Infinity ? this.length : end | 0

	  if (!encoding) encoding = 'utf8'
	  if (start < 0) start = 0
	  if (end > this.length) end = this.length
	  if (end <= start) return ''

	  while (true) {
	    switch (encoding) {
	      case 'hex':
	        return hexSlice(this, start, end)

	      case 'utf8':
	      case 'utf-8':
	        return utf8Slice(this, start, end)

	      case 'ascii':
	        return asciiSlice(this, start, end)

	      case 'binary':
	        return binarySlice(this, start, end)

	      case 'base64':
	        return base64Slice(this, start, end)

	      case 'ucs2':
	      case 'ucs-2':
	      case 'utf16le':
	      case 'utf-16le':
	        return utf16leSlice(this, start, end)

	      default:
	        if (loweredCase) throw new TypeError('Unknown encoding: ' + encoding)
	        encoding = (encoding + '').toLowerCase()
	        loweredCase = true
	    }
	  }
	}

	Buffer.prototype.equals = function equals (b) {
	  if (!Buffer.isBuffer(b)) throw new TypeError('Argument must be a Buffer')
	  if (this === b) return true
	  return Buffer.compare(this, b) === 0
	}

	Buffer.prototype.inspect = function inspect () {
	  var str = ''
	  var max = exports.INSPECT_MAX_BYTES
	  if (this.length > 0) {
	    str = this.toString('hex', 0, max).match(/.{2}/g).join(' ')
	    if (this.length > max) str += ' ... '
	  }
	  return '<Buffer ' + str + '>'
	}

	Buffer.prototype.compare = function compare (b) {
	  if (!Buffer.isBuffer(b)) throw new TypeError('Argument must be a Buffer')
	  if (this === b) return 0
	  return Buffer.compare(this, b)
	}

	Buffer.prototype.indexOf = function indexOf (val, byteOffset) {
	  if (byteOffset > 0x7fffffff) byteOffset = 0x7fffffff
	  else if (byteOffset < -0x80000000) byteOffset = -0x80000000
	  byteOffset >>= 0

	  if (this.length === 0) return -1
	  if (byteOffset >= this.length) return -1

	  // Negative offsets start from the end of the buffer
	  if (byteOffset < 0) byteOffset = Math.max(this.length + byteOffset, 0)

	  if (typeof val === 'string') {
	    if (val.length === 0) return -1 // special case: looking for empty string always fails
	    return String.prototype.indexOf.call(this, val, byteOffset)
	  }
	  if (Buffer.isBuffer(val)) {
	    return arrayIndexOf(this, val, byteOffset)
	  }
	  if (typeof val === 'number') {
	    if (Buffer.TYPED_ARRAY_SUPPORT && Uint8Array.prototype.indexOf === 'function') {
	      return Uint8Array.prototype.indexOf.call(this, val, byteOffset)
	    }
	    return arrayIndexOf(this, [ val ], byteOffset)
	  }

	  function arrayIndexOf (arr, val, byteOffset) {
	    var foundIndex = -1
	    for (var i = 0; byteOffset + i < arr.length; i++) {
	      if (arr[byteOffset + i] === val[foundIndex === -1 ? 0 : i - foundIndex]) {
	        if (foundIndex === -1) foundIndex = i
	        if (i - foundIndex + 1 === val.length) return byteOffset + foundIndex
	      } else {
	        foundIndex = -1
	      }
	    }
	    return -1
	  }

	  throw new TypeError('val must be string, number or Buffer')
	}

	// `get` will be removed in Node 0.13+
	Buffer.prototype.get = function get (offset) {
	  console.log('.get() is deprecated. Access using array indexes instead.')
	  return this.readUInt8(offset)
	}

	// `set` will be removed in Node 0.13+
	Buffer.prototype.set = function set (v, offset) {
	  console.log('.set() is deprecated. Access using array indexes instead.')
	  return this.writeUInt8(v, offset)
	}

	function hexWrite (buf, string, offset, length) {
	  offset = Number(offset) || 0
	  var remaining = buf.length - offset
	  if (!length) {
	    length = remaining
	  } else {
	    length = Number(length)
	    if (length > remaining) {
	      length = remaining
	    }
	  }

	  // must be an even number of digits
	  var strLen = string.length
	  if (strLen % 2 !== 0) throw new Error('Invalid hex string')

	  if (length > strLen / 2) {
	    length = strLen / 2
	  }
	  for (var i = 0; i < length; i++) {
	    var parsed = parseInt(string.substr(i * 2, 2), 16)
	    if (isNaN(parsed)) throw new Error('Invalid hex string')
	    buf[offset + i] = parsed
	  }
	  return i
	}

	function utf8Write (buf, string, offset, length) {
	  return blitBuffer(utf8ToBytes(string, buf.length - offset), buf, offset, length)
	}

	function asciiWrite (buf, string, offset, length) {
	  return blitBuffer(asciiToBytes(string), buf, offset, length)
	}

	function binaryWrite (buf, string, offset, length) {
	  return asciiWrite(buf, string, offset, length)
	}

	function base64Write (buf, string, offset, length) {
	  return blitBuffer(base64ToBytes(string), buf, offset, length)
	}

	function ucs2Write (buf, string, offset, length) {
	  return blitBuffer(utf16leToBytes(string, buf.length - offset), buf, offset, length)
	}

	Buffer.prototype.write = function write (string, offset, length, encoding) {
	  // Buffer#write(string)
	  if (offset === undefined) {
	    encoding = 'utf8'
	    length = this.length
	    offset = 0
	  // Buffer#write(string, encoding)
	  } else if (length === undefined && typeof offset === 'string') {
	    encoding = offset
	    length = this.length
	    offset = 0
	  // Buffer#write(string, offset[, length][, encoding])
	  } else if (isFinite(offset)) {
	    offset = offset | 0
	    if (isFinite(length)) {
	      length = length | 0
	      if (encoding === undefined) encoding = 'utf8'
	    } else {
	      encoding = length
	      length = undefined
	    }
	  // legacy write(string, encoding, offset, length) - remove in v0.13
	  } else {
	    var swap = encoding
	    encoding = offset
	    offset = length | 0
	    length = swap
	  }

	  var remaining = this.length - offset
	  if (length === undefined || length > remaining) length = remaining

	  if ((string.length > 0 && (length < 0 || offset < 0)) || offset > this.length) {
	    throw new RangeError('attempt to write outside buffer bounds')
	  }

	  if (!encoding) encoding = 'utf8'

	  var loweredCase = false
	  for (;;) {
	    switch (encoding) {
	      case 'hex':
	        return hexWrite(this, string, offset, length)

	      case 'utf8':
	      case 'utf-8':
	        return utf8Write(this, string, offset, length)

	      case 'ascii':
	        return asciiWrite(this, string, offset, length)

	      case 'binary':
	        return binaryWrite(this, string, offset, length)

	      case 'base64':
	        // Warning: maxLength not taken into account in base64Write
	        return base64Write(this, string, offset, length)

	      case 'ucs2':
	      case 'ucs-2':
	      case 'utf16le':
	      case 'utf-16le':
	        return ucs2Write(this, string, offset, length)

	      default:
	        if (loweredCase) throw new TypeError('Unknown encoding: ' + encoding)
	        encoding = ('' + encoding).toLowerCase()
	        loweredCase = true
	    }
	  }
	}

	Buffer.prototype.toJSON = function toJSON () {
	  return {
	    type: 'Buffer',
	    data: Array.prototype.slice.call(this._arr || this, 0)
	  }
	}

	function base64Slice (buf, start, end) {
	  if (start === 0 && end === buf.length) {
	    return base64.fromByteArray(buf)
	  } else {
	    return base64.fromByteArray(buf.slice(start, end))
	  }
	}

	function utf8Slice (buf, start, end) {
	  var res = ''
	  var tmp = ''
	  end = Math.min(buf.length, end)

	  for (var i = start; i < end; i++) {
	    if (buf[i] <= 0x7F) {
	      res += decodeUtf8Char(tmp) + String.fromCharCode(buf[i])
	      tmp = ''
	    } else {
	      tmp += '%' + buf[i].toString(16)
	    }
	  }

	  return res + decodeUtf8Char(tmp)
	}

	function asciiSlice (buf, start, end) {
	  var ret = ''
	  end = Math.min(buf.length, end)

	  for (var i = start; i < end; i++) {
	    ret += String.fromCharCode(buf[i] & 0x7F)
	  }
	  return ret
	}

	function binarySlice (buf, start, end) {
	  var ret = ''
	  end = Math.min(buf.length, end)

	  for (var i = start; i < end; i++) {
	    ret += String.fromCharCode(buf[i])
	  }
	  return ret
	}

	function hexSlice (buf, start, end) {
	  var len = buf.length

	  if (!start || start < 0) start = 0
	  if (!end || end < 0 || end > len) end = len

	  var out = ''
	  for (var i = start; i < end; i++) {
	    out += toHex(buf[i])
	  }
	  return out
	}

	function utf16leSlice (buf, start, end) {
	  var bytes = buf.slice(start, end)
	  var res = ''
	  for (var i = 0; i < bytes.length; i += 2) {
	    res += String.fromCharCode(bytes[i] + bytes[i + 1] * 256)
	  }
	  return res
	}

	Buffer.prototype.slice = function slice (start, end) {
	  var len = this.length
	  start = ~~start
	  end = end === undefined ? len : ~~end

	  if (start < 0) {
	    start += len
	    if (start < 0) start = 0
	  } else if (start > len) {
	    start = len
	  }

	  if (end < 0) {
	    end += len
	    if (end < 0) end = 0
	  } else if (end > len) {
	    end = len
	  }

	  if (end < start) end = start

	  var newBuf
	  if (Buffer.TYPED_ARRAY_SUPPORT) {
	    newBuf = Buffer._augment(this.subarray(start, end))
	  } else {
	    var sliceLen = end - start
	    newBuf = new Buffer(sliceLen, undefined)
	    for (var i = 0; i < sliceLen; i++) {
	      newBuf[i] = this[i + start]
	    }
	  }

	  if (newBuf.length) newBuf.parent = this.parent || this

	  return newBuf
	}

	/*
	 * Need to make sure that buffer isn't trying to write out of bounds.
	 */
	function checkOffset (offset, ext, length) {
	  if ((offset % 1) !== 0 || offset < 0) throw new RangeError('offset is not uint')
	  if (offset + ext > length) throw new RangeError('Trying to access beyond buffer length')
	}

	Buffer.prototype.readUIntLE = function readUIntLE (offset, byteLength, noAssert) {
	  offset = offset | 0
	  byteLength = byteLength | 0
	  if (!noAssert) checkOffset(offset, byteLength, this.length)

	  var val = this[offset]
	  var mul = 1
	  var i = 0
	  while (++i < byteLength && (mul *= 0x100)) {
	    val += this[offset + i] * mul
	  }

	  return val
	}

	Buffer.prototype.readUIntBE = function readUIntBE (offset, byteLength, noAssert) {
	  offset = offset | 0
	  byteLength = byteLength | 0
	  if (!noAssert) {
	    checkOffset(offset, byteLength, this.length)
	  }

	  var val = this[offset + --byteLength]
	  var mul = 1
	  while (byteLength > 0 && (mul *= 0x100)) {
	    val += this[offset + --byteLength] * mul
	  }

	  return val
	}

	Buffer.prototype.readUInt8 = function readUInt8 (offset, noAssert) {
	  if (!noAssert) checkOffset(offset, 1, this.length)
	  return this[offset]
	}

	Buffer.prototype.readUInt16LE = function readUInt16LE (offset, noAssert) {
	  if (!noAssert) checkOffset(offset, 2, this.length)
	  return this[offset] | (this[offset + 1] << 8)
	}

	Buffer.prototype.readUInt16BE = function readUInt16BE (offset, noAssert) {
	  if (!noAssert) checkOffset(offset, 2, this.length)
	  return (this[offset] << 8) | this[offset + 1]
	}

	Buffer.prototype.readUInt32LE = function readUInt32LE (offset, noAssert) {
	  if (!noAssert) checkOffset(offset, 4, this.length)

	  return ((this[offset]) |
	      (this[offset + 1] << 8) |
	      (this[offset + 2] << 16)) +
	      (this[offset + 3] * 0x1000000)
	}

	Buffer.prototype.readUInt32BE = function readUInt32BE (offset, noAssert) {
	  if (!noAssert) checkOffset(offset, 4, this.length)

	  return (this[offset] * 0x1000000) +
	    ((this[offset + 1] << 16) |
	    (this[offset + 2] << 8) |
	    this[offset + 3])
	}

	Buffer.prototype.readIntLE = function readIntLE (offset, byteLength, noAssert) {
	  offset = offset | 0
	  byteLength = byteLength | 0
	  if (!noAssert) checkOffset(offset, byteLength, this.length)

	  var val = this[offset]
	  var mul = 1
	  var i = 0
	  while (++i < byteLength && (mul *= 0x100)) {
	    val += this[offset + i] * mul
	  }
	  mul *= 0x80

	  if (val >= mul) val -= Math.pow(2, 8 * byteLength)

	  return val
	}

	Buffer.prototype.readIntBE = function readIntBE (offset, byteLength, noAssert) {
	  offset = offset | 0
	  byteLength = byteLength | 0
	  if (!noAssert) checkOffset(offset, byteLength, this.length)

	  var i = byteLength
	  var mul = 1
	  var val = this[offset + --i]
	  while (i > 0 && (mul *= 0x100)) {
	    val += this[offset + --i] * mul
	  }
	  mul *= 0x80

	  if (val >= mul) val -= Math.pow(2, 8 * byteLength)

	  return val
	}

	Buffer.prototype.readInt8 = function readInt8 (offset, noAssert) {
	  if (!noAssert) checkOffset(offset, 1, this.length)
	  if (!(this[offset] & 0x80)) return (this[offset])
	  return ((0xff - this[offset] + 1) * -1)
	}

	Buffer.prototype.readInt16LE = function readInt16LE (offset, noAssert) {
	  if (!noAssert) checkOffset(offset, 2, this.length)
	  var val = this[offset] | (this[offset + 1] << 8)
	  return (val & 0x8000) ? val | 0xFFFF0000 : val
	}

	Buffer.prototype.readInt16BE = function readInt16BE (offset, noAssert) {
	  if (!noAssert) checkOffset(offset, 2, this.length)
	  var val = this[offset + 1] | (this[offset] << 8)
	  return (val & 0x8000) ? val | 0xFFFF0000 : val
	}

	Buffer.prototype.readInt32LE = function readInt32LE (offset, noAssert) {
	  if (!noAssert) checkOffset(offset, 4, this.length)

	  return (this[offset]) |
	    (this[offset + 1] << 8) |
	    (this[offset + 2] << 16) |
	    (this[offset + 3] << 24)
	}

	Buffer.prototype.readInt32BE = function readInt32BE (offset, noAssert) {
	  if (!noAssert) checkOffset(offset, 4, this.length)

	  return (this[offset] << 24) |
	    (this[offset + 1] << 16) |
	    (this[offset + 2] << 8) |
	    (this[offset + 3])
	}

	Buffer.prototype.readFloatLE = function readFloatLE (offset, noAssert) {
	  if (!noAssert) checkOffset(offset, 4, this.length)
	  return ieee754.read(this, offset, true, 23, 4)
	}

	Buffer.prototype.readFloatBE = function readFloatBE (offset, noAssert) {
	  if (!noAssert) checkOffset(offset, 4, this.length)
	  return ieee754.read(this, offset, false, 23, 4)
	}

	Buffer.prototype.readDoubleLE = function readDoubleLE (offset, noAssert) {
	  if (!noAssert) checkOffset(offset, 8, this.length)
	  return ieee754.read(this, offset, true, 52, 8)
	}

	Buffer.prototype.readDoubleBE = function readDoubleBE (offset, noAssert) {
	  if (!noAssert) checkOffset(offset, 8, this.length)
	  return ieee754.read(this, offset, false, 52, 8)
	}

	function checkInt (buf, value, offset, ext, max, min) {
	  if (!Buffer.isBuffer(buf)) throw new TypeError('buffer must be a Buffer instance')
	  if (value > max || value < min) throw new RangeError('value is out of bounds')
	  if (offset + ext > buf.length) throw new RangeError('index out of range')
	}

	Buffer.prototype.writeUIntLE = function writeUIntLE (value, offset, byteLength, noAssert) {
	  value = +value
	  offset = offset | 0
	  byteLength = byteLength | 0
	  if (!noAssert) checkInt(this, value, offset, byteLength, Math.pow(2, 8 * byteLength), 0)

	  var mul = 1
	  var i = 0
	  this[offset] = value & 0xFF
	  while (++i < byteLength && (mul *= 0x100)) {
	    this[offset + i] = (value / mul) & 0xFF
	  }

	  return offset + byteLength
	}

	Buffer.prototype.writeUIntBE = function writeUIntBE (value, offset, byteLength, noAssert) {
	  value = +value
	  offset = offset | 0
	  byteLength = byteLength | 0
	  if (!noAssert) checkInt(this, value, offset, byteLength, Math.pow(2, 8 * byteLength), 0)

	  var i = byteLength - 1
	  var mul = 1
	  this[offset + i] = value & 0xFF
	  while (--i >= 0 && (mul *= 0x100)) {
	    this[offset + i] = (value / mul) & 0xFF
	  }

	  return offset + byteLength
	}

	Buffer.prototype.writeUInt8 = function writeUInt8 (value, offset, noAssert) {
	  value = +value
	  offset = offset | 0
	  if (!noAssert) checkInt(this, value, offset, 1, 0xff, 0)
	  if (!Buffer.TYPED_ARRAY_SUPPORT) value = Math.floor(value)
	  this[offset] = value
	  return offset + 1
	}

	function objectWriteUInt16 (buf, value, offset, littleEndian) {
	  if (value < 0) value = 0xffff + value + 1
	  for (var i = 0, j = Math.min(buf.length - offset, 2); i < j; i++) {
	    buf[offset + i] = (value & (0xff << (8 * (littleEndian ? i : 1 - i)))) >>>
	      (littleEndian ? i : 1 - i) * 8
	  }
	}

	Buffer.prototype.writeUInt16LE = function writeUInt16LE (value, offset, noAssert) {
	  value = +value
	  offset = offset | 0
	  if (!noAssert) checkInt(this, value, offset, 2, 0xffff, 0)
	  if (Buffer.TYPED_ARRAY_SUPPORT) {
	    this[offset] = value
	    this[offset + 1] = (value >>> 8)
	  } else {
	    objectWriteUInt16(this, value, offset, true)
	  }
	  return offset + 2
	}

	Buffer.prototype.writeUInt16BE = function writeUInt16BE (value, offset, noAssert) {
	  value = +value
	  offset = offset | 0
	  if (!noAssert) checkInt(this, value, offset, 2, 0xffff, 0)
	  if (Buffer.TYPED_ARRAY_SUPPORT) {
	    this[offset] = (value >>> 8)
	    this[offset + 1] = value
	  } else {
	    objectWriteUInt16(this, value, offset, false)
	  }
	  return offset + 2
	}

	function objectWriteUInt32 (buf, value, offset, littleEndian) {
	  if (value < 0) value = 0xffffffff + value + 1
	  for (var i = 0, j = Math.min(buf.length - offset, 4); i < j; i++) {
	    buf[offset + i] = (value >>> (littleEndian ? i : 3 - i) * 8) & 0xff
	  }
	}

	Buffer.prototype.writeUInt32LE = function writeUInt32LE (value, offset, noAssert) {
	  value = +value
	  offset = offset | 0
	  if (!noAssert) checkInt(this, value, offset, 4, 0xffffffff, 0)
	  if (Buffer.TYPED_ARRAY_SUPPORT) {
	    this[offset + 3] = (value >>> 24)
	    this[offset + 2] = (value >>> 16)
	    this[offset + 1] = (value >>> 8)
	    this[offset] = value
	  } else {
	    objectWriteUInt32(this, value, offset, true)
	  }
	  return offset + 4
	}

	Buffer.prototype.writeUInt32BE = function writeUInt32BE (value, offset, noAssert) {
	  value = +value
	  offset = offset | 0
	  if (!noAssert) checkInt(this, value, offset, 4, 0xffffffff, 0)
	  if (Buffer.TYPED_ARRAY_SUPPORT) {
	    this[offset] = (value >>> 24)
	    this[offset + 1] = (value >>> 16)
	    this[offset + 2] = (value >>> 8)
	    this[offset + 3] = value
	  } else {
	    objectWriteUInt32(this, value, offset, false)
	  }
	  return offset + 4
	}

	Buffer.prototype.writeIntLE = function writeIntLE (value, offset, byteLength, noAssert) {
	  value = +value
	  offset = offset | 0
	  if (!noAssert) {
	    var limit = Math.pow(2, 8 * byteLength - 1)

	    checkInt(this, value, offset, byteLength, limit - 1, -limit)
	  }

	  var i = 0
	  var mul = 1
	  var sub = value < 0 ? 1 : 0
	  this[offset] = value & 0xFF
	  while (++i < byteLength && (mul *= 0x100)) {
	    this[offset + i] = ((value / mul) >> 0) - sub & 0xFF
	  }

	  return offset + byteLength
	}

	Buffer.prototype.writeIntBE = function writeIntBE (value, offset, byteLength, noAssert) {
	  value = +value
	  offset = offset | 0
	  if (!noAssert) {
	    var limit = Math.pow(2, 8 * byteLength - 1)

	    checkInt(this, value, offset, byteLength, limit - 1, -limit)
	  }

	  var i = byteLength - 1
	  var mul = 1
	  var sub = value < 0 ? 1 : 0
	  this[offset + i] = value & 0xFF
	  while (--i >= 0 && (mul *= 0x100)) {
	    this[offset + i] = ((value / mul) >> 0) - sub & 0xFF
	  }

	  return offset + byteLength
	}

	Buffer.prototype.writeInt8 = function writeInt8 (value, offset, noAssert) {
	  value = +value
	  offset = offset | 0
	  if (!noAssert) checkInt(this, value, offset, 1, 0x7f, -0x80)
	  if (!Buffer.TYPED_ARRAY_SUPPORT) value = Math.floor(value)
	  if (value < 0) value = 0xff + value + 1
	  this[offset] = value
	  return offset + 1
	}

	Buffer.prototype.writeInt16LE = function writeInt16LE (value, offset, noAssert) {
	  value = +value
	  offset = offset | 0
	  if (!noAssert) checkInt(this, value, offset, 2, 0x7fff, -0x8000)
	  if (Buffer.TYPED_ARRAY_SUPPORT) {
	    this[offset] = value
	    this[offset + 1] = (value >>> 8)
	  } else {
	    objectWriteUInt16(this, value, offset, true)
	  }
	  return offset + 2
	}

	Buffer.prototype.writeInt16BE = function writeInt16BE (value, offset, noAssert) {
	  value = +value
	  offset = offset | 0
	  if (!noAssert) checkInt(this, value, offset, 2, 0x7fff, -0x8000)
	  if (Buffer.TYPED_ARRAY_SUPPORT) {
	    this[offset] = (value >>> 8)
	    this[offset + 1] = value
	  } else {
	    objectWriteUInt16(this, value, offset, false)
	  }
	  return offset + 2
	}

	Buffer.prototype.writeInt32LE = function writeInt32LE (value, offset, noAssert) {
	  value = +value
	  offset = offset | 0
	  if (!noAssert) checkInt(this, value, offset, 4, 0x7fffffff, -0x80000000)
	  if (Buffer.TYPED_ARRAY_SUPPORT) {
	    this[offset] = value
	    this[offset + 1] = (value >>> 8)
	    this[offset + 2] = (value >>> 16)
	    this[offset + 3] = (value >>> 24)
	  } else {
	    objectWriteUInt32(this, value, offset, true)
	  }
	  return offset + 4
	}

	Buffer.prototype.writeInt32BE = function writeInt32BE (value, offset, noAssert) {
	  value = +value
	  offset = offset | 0
	  if (!noAssert) checkInt(this, value, offset, 4, 0x7fffffff, -0x80000000)
	  if (value < 0) value = 0xffffffff + value + 1
	  if (Buffer.TYPED_ARRAY_SUPPORT) {
	    this[offset] = (value >>> 24)
	    this[offset + 1] = (value >>> 16)
	    this[offset + 2] = (value >>> 8)
	    this[offset + 3] = value
	  } else {
	    objectWriteUInt32(this, value, offset, false)
	  }
	  return offset + 4
	}

	function checkIEEE754 (buf, value, offset, ext, max, min) {
	  if (value > max || value < min) throw new RangeError('value is out of bounds')
	  if (offset + ext > buf.length) throw new RangeError('index out of range')
	  if (offset < 0) throw new RangeError('index out of range')
	}

	function writeFloat (buf, value, offset, littleEndian, noAssert) {
	  if (!noAssert) {
	    checkIEEE754(buf, value, offset, 4, 3.4028234663852886e+38, -3.4028234663852886e+38)
	  }
	  ieee754.write(buf, value, offset, littleEndian, 23, 4)
	  return offset + 4
	}

	Buffer.prototype.writeFloatLE = function writeFloatLE (value, offset, noAssert) {
	  return writeFloat(this, value, offset, true, noAssert)
	}

	Buffer.prototype.writeFloatBE = function writeFloatBE (value, offset, noAssert) {
	  return writeFloat(this, value, offset, false, noAssert)
	}

	function writeDouble (buf, value, offset, littleEndian, noAssert) {
	  if (!noAssert) {
	    checkIEEE754(buf, value, offset, 8, 1.7976931348623157E+308, -1.7976931348623157E+308)
	  }
	  ieee754.write(buf, value, offset, littleEndian, 52, 8)
	  return offset + 8
	}

	Buffer.prototype.writeDoubleLE = function writeDoubleLE (value, offset, noAssert) {
	  return writeDouble(this, value, offset, true, noAssert)
	}

	Buffer.prototype.writeDoubleBE = function writeDoubleBE (value, offset, noAssert) {
	  return writeDouble(this, value, offset, false, noAssert)
	}

	// copy(targetBuffer, targetStart=0, sourceStart=0, sourceEnd=buffer.length)
	Buffer.prototype.copy = function copy (target, targetStart, start, end) {
	  if (!start) start = 0
	  if (!end && end !== 0) end = this.length
	  if (targetStart >= target.length) targetStart = target.length
	  if (!targetStart) targetStart = 0
	  if (end > 0 && end < start) end = start

	  // Copy 0 bytes; we're done
	  if (end === start) return 0
	  if (target.length === 0 || this.length === 0) return 0

	  // Fatal error conditions
	  if (targetStart < 0) {
	    throw new RangeError('targetStart out of bounds')
	  }
	  if (start < 0 || start >= this.length) throw new RangeError('sourceStart out of bounds')
	  if (end < 0) throw new RangeError('sourceEnd out of bounds')

	  // Are we oob?
	  if (end > this.length) end = this.length
	  if (target.length - targetStart < end - start) {
	    end = target.length - targetStart + start
	  }

	  var len = end - start

	  if (len < 1000 || !Buffer.TYPED_ARRAY_SUPPORT) {
	    for (var i = 0; i < len; i++) {
	      target[i + targetStart] = this[i + start]
	    }
	  } else {
	    target._set(this.subarray(start, start + len), targetStart)
	  }

	  return len
	}

	// fill(value, start=0, end=buffer.length)
	Buffer.prototype.fill = function fill (value, start, end) {
	  if (!value) value = 0
	  if (!start) start = 0
	  if (!end) end = this.length

	  if (end < start) throw new RangeError('end < start')

	  // Fill 0 bytes; we're done
	  if (end === start) return
	  if (this.length === 0) return

	  if (start < 0 || start >= this.length) throw new RangeError('start out of bounds')
	  if (end < 0 || end > this.length) throw new RangeError('end out of bounds')

	  var i
	  if (typeof value === 'number') {
	    for (i = start; i < end; i++) {
	      this[i] = value
	    }
	  } else {
	    var bytes = utf8ToBytes(value.toString())
	    var len = bytes.length
	    for (i = start; i < end; i++) {
	      this[i] = bytes[i % len]
	    }
	  }

	  return this
	}

	/**
	 * Creates a new `ArrayBuffer` with the *copied* memory of the buffer instance.
	 * Added in Node 0.12. Only available in browsers that support ArrayBuffer.
	 */
	Buffer.prototype.toArrayBuffer = function toArrayBuffer () {
	  if (typeof Uint8Array !== 'undefined') {
	    if (Buffer.TYPED_ARRAY_SUPPORT) {
	      return (new Buffer(this)).buffer
	    } else {
	      var buf = new Uint8Array(this.length)
	      for (var i = 0, len = buf.length; i < len; i += 1) {
	        buf[i] = this[i]
	      }
	      return buf.buffer
	    }
	  } else {
	    throw new TypeError('Buffer.toArrayBuffer not supported in this browser')
	  }
	}

	// HELPER FUNCTIONS
	// ================

	var BP = Buffer.prototype

	/**
	 * Augment a Uint8Array *instance* (not the Uint8Array class!) with Buffer methods
	 */
	Buffer._augment = function _augment (arr) {
	  arr.constructor = Buffer
	  arr._isBuffer = true

	  // save reference to original Uint8Array set method before overwriting
	  arr._set = arr.set

	  // deprecated, will be removed in node 0.13+
	  arr.get = BP.get
	  arr.set = BP.set

	  arr.write = BP.write
	  arr.toString = BP.toString
	  arr.toLocaleString = BP.toString
	  arr.toJSON = BP.toJSON
	  arr.equals = BP.equals
	  arr.compare = BP.compare
	  arr.indexOf = BP.indexOf
	  arr.copy = BP.copy
	  arr.slice = BP.slice
	  arr.readUIntLE = BP.readUIntLE
	  arr.readUIntBE = BP.readUIntBE
	  arr.readUInt8 = BP.readUInt8
	  arr.readUInt16LE = BP.readUInt16LE
	  arr.readUInt16BE = BP.readUInt16BE
	  arr.readUInt32LE = BP.readUInt32LE
	  arr.readUInt32BE = BP.readUInt32BE
	  arr.readIntLE = BP.readIntLE
	  arr.readIntBE = BP.readIntBE
	  arr.readInt8 = BP.readInt8
	  arr.readInt16LE = BP.readInt16LE
	  arr.readInt16BE = BP.readInt16BE
	  arr.readInt32LE = BP.readInt32LE
	  arr.readInt32BE = BP.readInt32BE
	  arr.readFloatLE = BP.readFloatLE
	  arr.readFloatBE = BP.readFloatBE
	  arr.readDoubleLE = BP.readDoubleLE
	  arr.readDoubleBE = BP.readDoubleBE
	  arr.writeUInt8 = BP.writeUInt8
	  arr.writeUIntLE = BP.writeUIntLE
	  arr.writeUIntBE = BP.writeUIntBE
	  arr.writeUInt16LE = BP.writeUInt16LE
	  arr.writeUInt16BE = BP.writeUInt16BE
	  arr.writeUInt32LE = BP.writeUInt32LE
	  arr.writeUInt32BE = BP.writeUInt32BE
	  arr.writeIntLE = BP.writeIntLE
	  arr.writeIntBE = BP.writeIntBE
	  arr.writeInt8 = BP.writeInt8
	  arr.writeInt16LE = BP.writeInt16LE
	  arr.writeInt16BE = BP.writeInt16BE
	  arr.writeInt32LE = BP.writeInt32LE
	  arr.writeInt32BE = BP.writeInt32BE
	  arr.writeFloatLE = BP.writeFloatLE
	  arr.writeFloatBE = BP.writeFloatBE
	  arr.writeDoubleLE = BP.writeDoubleLE
	  arr.writeDoubleBE = BP.writeDoubleBE
	  arr.fill = BP.fill
	  arr.inspect = BP.inspect
	  arr.toArrayBuffer = BP.toArrayBuffer

	  return arr
	}

	var INVALID_BASE64_RE = /[^+\/0-9A-z\-]/g

	function base64clean (str) {
	  // Node strips out invalid characters like \n and \t from the string, base64-js does not
	  str = stringtrim(str).replace(INVALID_BASE64_RE, '')
	  // Node converts strings with length < 2 to ''
	  if (str.length < 2) return ''
	  // Node allows for non-padded base64 strings (missing trailing ===), base64-js does not
	  while (str.length % 4 !== 0) {
	    str = str + '='
	  }
	  return str
	}

	function stringtrim (str) {
	  if (str.trim) return str.trim()
	  return str.replace(/^\s+|\s+$/g, '')
	}

	function toHex (n) {
	  if (n < 16) return '0' + n.toString(16)
	  return n.toString(16)
	}

	function utf8ToBytes (string, units) {
	  units = units || Infinity
	  var codePoint
	  var length = string.length
	  var leadSurrogate = null
	  var bytes = []
	  var i = 0

	  for (; i < length; i++) {
	    codePoint = string.charCodeAt(i)

	    // is surrogate component
	    if (codePoint > 0xD7FF && codePoint < 0xE000) {
	      // last char was a lead
	      if (leadSurrogate) {
	        // 2 leads in a row
	        if (codePoint < 0xDC00) {
	          if ((units -= 3) > -1) bytes.push(0xEF, 0xBF, 0xBD)
	          leadSurrogate = codePoint
	          continue
	        } else {
	          // valid surrogate pair
	          codePoint = leadSurrogate - 0xD800 << 10 | codePoint - 0xDC00 | 0x10000
	          leadSurrogate = null
	        }
	      } else {
	        // no lead yet

	        if (codePoint > 0xDBFF) {
	          // unexpected trail
	          if ((units -= 3) > -1) bytes.push(0xEF, 0xBF, 0xBD)
	          continue
	        } else if (i + 1 === length) {
	          // unpaired lead
	          if ((units -= 3) > -1) bytes.push(0xEF, 0xBF, 0xBD)
	          continue
	        } else {
	          // valid lead
	          leadSurrogate = codePoint
	          continue
	        }
	      }
	    } else if (leadSurrogate) {
	      // valid bmp char, but last char was a lead
	      if ((units -= 3) > -1) bytes.push(0xEF, 0xBF, 0xBD)
	      leadSurrogate = null
	    }

	    // encode utf8
	    if (codePoint < 0x80) {
	      if ((units -= 1) < 0) break
	      bytes.push(codePoint)
	    } else if (codePoint < 0x800) {
	      if ((units -= 2) < 0) break
	      bytes.push(
	        codePoint >> 0x6 | 0xC0,
	        codePoint & 0x3F | 0x80
	      )
	    } else if (codePoint < 0x10000) {
	      if ((units -= 3) < 0) break
	      bytes.push(
	        codePoint >> 0xC | 0xE0,
	        codePoint >> 0x6 & 0x3F | 0x80,
	        codePoint & 0x3F | 0x80
	      )
	    } else if (codePoint < 0x200000) {
	      if ((units -= 4) < 0) break
	      bytes.push(
	        codePoint >> 0x12 | 0xF0,
	        codePoint >> 0xC & 0x3F | 0x80,
	        codePoint >> 0x6 & 0x3F | 0x80,
	        codePoint & 0x3F | 0x80
	      )
	    } else {
	      throw new Error('Invalid code point')
	    }
	  }

	  return bytes
	}

	function asciiToBytes (str) {
	  var byteArray = []
	  for (var i = 0; i < str.length; i++) {
	    // Node's code seems to be doing this and not & 0x7F..
	    byteArray.push(str.charCodeAt(i) & 0xFF)
	  }
	  return byteArray
	}

	function utf16leToBytes (str, units) {
	  var c, hi, lo
	  var byteArray = []
	  for (var i = 0; i < str.length; i++) {
	    if ((units -= 2) < 0) break

	    c = str.charCodeAt(i)
	    hi = c >> 8
	    lo = c % 256
	    byteArray.push(lo)
	    byteArray.push(hi)
	  }

	  return byteArray
	}

	function base64ToBytes (str) {
	  return base64.toByteArray(base64clean(str))
	}

	function blitBuffer (src, dst, offset, length) {
	  for (var i = 0; i < length; i++) {
	    if ((i + offset >= dst.length) || (i >= src.length)) break
	    dst[i + offset] = src[i]
	  }
	  return i
	}

	function decodeUtf8Char (str) {
	  try {
	    return decodeURIComponent(str)
	  } catch (err) {
	    return String.fromCharCode(0xFFFD) // UTF 8 invalid char
	  }
	}

	/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(4).Buffer))

/***/ },
/* 5 */
/***/ function(module, exports, __webpack_require__) {

	/* jslint node: true */
	'use strict';

	var _ = __webpack_require__(11);
	var FontWrapper = __webpack_require__(16);

	function typeName(bold, italics){
		var type = 'normal';
		if (bold && italics) type = 'bolditalics';
		else if (bold) type = 'bold';
		else if (italics) type = 'italics';
		return type;
	}

	function FontProvider(fontDescriptors, pdfDoc) {
		this.fonts = {};
		this.pdfDoc = pdfDoc;
		this.fontWrappers = {};

		for(var font in fontDescriptors) {
			if (fontDescriptors.hasOwnProperty(font)) {
				var fontDef = fontDescriptors[font];

				this.fonts[font] = {
					normal: fontDef.normal,
					bold: fontDef.bold,
					italics: fontDef.italics,
					bolditalics: fontDef.bolditalics
				};
			}
		}
	}

	FontProvider.prototype.provideFont = function(familyName, bold, italics) {
	  if (!this.fonts[familyName]) return this.pdfDoc._font;
		var type = typeName(bold, italics);

	  this.fontWrappers[familyName] = this.fontWrappers[familyName] || {};

	  if (!this.fontWrappers[familyName][type]) {
			this.fontWrappers[familyName][type] = new FontWrapper(this.pdfDoc, this.fonts[familyName][type], familyName + '(' + type + ')');
		}

	  return this.fontWrappers[familyName][type];
	};

	FontProvider.prototype.setFontRefsToPdfDoc = function(){
	  var self = this;

	  _.each(self.fontWrappers, function(fontFamily) {
	    _.each(fontFamily, function(fontWrapper){
	      _.each(fontWrapper.pdfFonts, function(font){
	        if (!self.pdfDoc.page.fonts[font.id]) {
	          self.pdfDoc.page.fonts[font.id] = font.ref();
	        }
	      });
	    });
	  });
	};

	module.exports = FontProvider;


/***/ },
/* 6 */
/***/ function(module, exports, __webpack_require__) {

	/* jslint node: true */
	'use strict';

	var _ = __webpack_require__(11);
	var TraversalTracker = __webpack_require__(18);
	var DocMeasure = __webpack_require__(19);
	var DocumentContext = __webpack_require__(20);
	var PageElementWriter = __webpack_require__(21);
	var ColumnCalculator = __webpack_require__(22);
	var TableProcessor = __webpack_require__(23);
	var Line = __webpack_require__(24);
	var pack = __webpack_require__(25).pack;
	var offsetVector = __webpack_require__(25).offsetVector;
	var fontStringify = __webpack_require__(25).fontStringify;
	var isFunction = __webpack_require__(25).isFunction;
	var TextTools = __webpack_require__(26);
	var StyleContextStack = __webpack_require__(27);

	function addAll(target, otherArray){
	  _.each(otherArray, function(item){
	    target.push(item);
	  });
	}

	/**
	 * Creates an instance of LayoutBuilder - layout engine which turns document-definition-object
	 * into a set of pages, lines, inlines and vectors ready to be rendered into a PDF
	 *
	 * @param {Object} pageSize - an object defining page width and height
	 * @param {Object} pageMargins - an object defining top, left, right and bottom margins
	 */
	function LayoutBuilder(pageSize, pageMargins, imageMeasure) {
		this.pageSize = pageSize;
		this.pageMargins = pageMargins;
		this.tracker = new TraversalTracker();
	    this.imageMeasure = imageMeasure;
	    this.tableLayouts = {};
	}

	LayoutBuilder.prototype.registerTableLayouts = function (tableLayouts) {
	  this.tableLayouts = pack(this.tableLayouts, tableLayouts);
	};

	/**
	 * Executes layout engine on document-definition-object and creates an array of pages
	 * containing positioned Blocks, Lines and inlines
	 *
	 * @param {Object} docStructure document-definition-object
	 * @param {Object} fontProvider font provider
	 * @param {Object} styleDictionary dictionary with style definitions
	 * @param {Object} defaultStyle default style definition
	 * @return {Array} an array of pages
	 */
	LayoutBuilder.prototype.layoutDocument = function (docStructure, fontProvider, styleDictionary, defaultStyle, background, header, footer, images, watermark, pageBreakBeforeFct) {

	  function addPageBreaksIfNecessary(linearNodeList, pages) {
	    linearNodeList = _.reject(linearNodeList, function(node){
	      return _.isEmpty(node.positions);
	    });

	    _.each(linearNodeList, function(node) {
	      var nodeInfo = _.pick(node, [
	        'id', 'text', 'ul', 'ol', 'table', 'image', 'qr', 'canvas', 'columns',
	        'headlineLevel', 'style', 'pageBreak', 'pageOrientation',
	        'width', 'height'
	      ]);
	      nodeInfo.startPosition = _.first(node.positions);
	      nodeInfo.pageNumbers = _.chain(node.positions).map('pageNumber').uniq().value();
	      nodeInfo.pages = pages.length;
	      nodeInfo.stack = _.isArray(node.stack);

	      node.nodeInfo = nodeInfo;
	    });

	    return _.any(linearNodeList, function (node, index, followingNodeList) {
	      if (node.pageBreak !== 'before' && !node.pageBreakCalculated) {
	        node.pageBreakCalculated = true;
	        var pageNumber = _.first(node.nodeInfo.pageNumbers);

					var followingNodesOnPage = _.chain(followingNodeList).drop(index + 1).filter(function (node0) {
	          return _.contains(node0.nodeInfo.pageNumbers, pageNumber);
	        }).value();

	        var nodesOnNextPage = _.chain(followingNodeList).drop(index + 1).filter(function (node0) {
	          return _.contains(node0.nodeInfo.pageNumbers, pageNumber + 1);
	        }).value();

	        var previousNodesOnPage = _.chain(followingNodeList).take(index).filter(function (node0) {
	          return _.contains(node0.nodeInfo.pageNumbers, pageNumber);
	        }).value();

	        if (pageBreakBeforeFct(node.nodeInfo,
	          _.map(followingNodesOnPage, 'nodeInfo'),
	          _.map(nodesOnNextPage, 'nodeInfo'),
	          _.map(previousNodesOnPage, 'nodeInfo'))) {
	          node.pageBreak = 'before';
	          return true;
	        }
	      }
	    });
	  }

	  if(!isFunction(pageBreakBeforeFct)){
	    pageBreakBeforeFct = function(){
	      return false;
	    };
	  }

	  this.docMeasure = new DocMeasure(fontProvider, styleDictionary, defaultStyle, this.imageMeasure, this.tableLayouts, images);


	  function resetXYs(result) {
	    _.each(result.linearNodeList, function (node) {
	      node.resetXY();
	    });
	  }

	  var result = this.tryLayoutDocument(docStructure, fontProvider, styleDictionary, defaultStyle, background, header, footer, images, watermark);
	  while(addPageBreaksIfNecessary(result.linearNodeList, result.pages)){
	    resetXYs(result);
	    result = this.tryLayoutDocument(docStructure, fontProvider, styleDictionary, defaultStyle, background, header, footer, images, watermark);
	  }

		return result.pages;
	};

	LayoutBuilder.prototype.tryLayoutDocument = function (docStructure, fontProvider, styleDictionary, defaultStyle, background, header, footer, images, watermark, pageBreakBeforeFct) {

	  this.linearNodeList = [];
	  docStructure = this.docMeasure.measureDocument(docStructure);

	  this.writer = new PageElementWriter(
	    new DocumentContext(this.pageSize, this.pageMargins), this.tracker);

	  var _this = this;
	  this.writer.context().tracker.startTracking('pageAdded', function() {
	    _this.addBackground(background);
	  });

	  this.addBackground(background);
	  this.processNode(docStructure);
	  this.addHeadersAndFooters(header, footer);
	  /* jshint eqnull:true */
	  if(watermark != null)
	    this.addWatermark(watermark, fontProvider);

	  return {pages: this.writer.context().pages, linearNodeList: this.linearNodeList};
	};


	LayoutBuilder.prototype.addBackground = function(background) {
	    var backgroundGetter = isFunction(background) ? background : function() { return background; };

	    var pageBackground = backgroundGetter(this.writer.context().page + 1);

	    if (pageBackground) {
	      var pageSize = this.writer.context().getCurrentPage().pageSize;
	      this.writer.beginUnbreakableBlock(pageSize.width, pageSize.height);
	      this.processNode(this.docMeasure.measureDocument(pageBackground));
	      this.writer.commitUnbreakableBlock(0, 0);
	    }
	};

	LayoutBuilder.prototype.addStaticRepeatable = function(headerOrFooter, sizeFunction) {
	  this.addDynamicRepeatable(function() { return headerOrFooter; }, sizeFunction);
	};

	LayoutBuilder.prototype.addDynamicRepeatable = function(nodeGetter, sizeFunction) {
	  var pages = this.writer.context().pages;

	  for(var pageIndex = 0, l = pages.length; pageIndex < l; pageIndex++) {
	    this.writer.context().page = pageIndex;

	    var node = nodeGetter(pageIndex + 1, l);

	    if (node) {
	      var sizes = sizeFunction(this.writer.context().getCurrentPage().pageSize, this.pageMargins);
	      this.writer.beginUnbreakableBlock(sizes.width, sizes.height);
	      this.processNode(this.docMeasure.measureDocument(node));
	      this.writer.commitUnbreakableBlock(sizes.x, sizes.y);
	    }
	  }
	};

	LayoutBuilder.prototype.addHeadersAndFooters = function(header, footer) {
	  var headerSizeFct = function(pageSize, pageMargins){
	    return {
	      x: 0,
	      y: 0,
	      width: pageSize.width,
	      height: pageMargins.top
	    };
	  };

	  var footerSizeFct = function (pageSize, pageMargins) {
	    return {
	      x: 0,
	      y: pageSize.height - pageMargins.bottom,
	      width: pageSize.width,
	      height: pageMargins.bottom
	    };
	  };

	  if(isFunction(header)) {
	    this.addDynamicRepeatable(header, headerSizeFct);
	  } else if(header) {
	    this.addStaticRepeatable(header, headerSizeFct);
	  }

	  if(isFunction(footer)) {
	    this.addDynamicRepeatable(footer, footerSizeFct);
	  } else if(footer) {
	    this.addStaticRepeatable(footer, footerSizeFct);
	  }
	};

	LayoutBuilder.prototype.addWatermark = function(watermark, fontProvider){
	  var defaultFont = Object.getOwnPropertyNames(fontProvider.fonts)[0]; // TODO allow selection of other font
	  var watermarkObject = {
	    text: watermark,
	    font: fontProvider.provideFont(fontProvider[defaultFont], false, false),
	    size: getSize(this.pageSize, watermark, fontProvider)
	  };

	  var pages = this.writer.context().pages;
	  for(var i = 0, l = pages.length; i < l; i++) {
	    pages[i].watermark = watermarkObject;
	  }

	  function getSize(pageSize, watermark, fontProvider){
	    var width = pageSize.width;
	    var height = pageSize.height;
	    var targetWidth = Math.sqrt(width*width + height*height)*0.8; /* page diagnoal * sample factor */
	    var textTools = new TextTools(fontProvider);
	    var styleContextStack = new StyleContextStack();
	    var size;

	    /**
	     * Binary search the best font size.
	     * Initial bounds [0, 1000]
	     * Break when range < 1
	     */
	    var a = 0;
	    var b = 1000;
	    var c = (a+b)/2;
	    while(Math.abs(a - b) > 1){
	      styleContextStack.push({
	        fontSize: c
	      });
	      size = textTools.sizeOfString(watermark, styleContextStack);
	      if(size.width > targetWidth){
	        b = c;
	        c = (a+b)/2;
	      }
	      else if(size.width < targetWidth){
	        a = c;
	        c = (a+b)/2;
	      }
	      styleContextStack.pop();
	    }
	    /*
	      End binary search
	     */
	    return {size: size, fontSize: c};
	  }
	};

	function decorateNode(node){
	  var x = node.x, y = node.y;
	  node.positions = [];

	  _.each(node.canvas, function(vector){
	    var x = vector.x, y = vector.y;
	    vector.resetXY = function(){
	      vector.x = x;
	      vector.y = y;
	    };
	  });

	  node.resetXY = function(){
	    node.x = x;
	    node.y = y;
	    _.each(node.canvas, function(vector){
	      vector.resetXY();
	    });
	  };
	}

	LayoutBuilder.prototype.processNode = function(node) {
	  var self = this;

	  this.linearNodeList.push(node);
	  decorateNode(node);

	  applyMargins(function() {
	    var absPosition = node.absolutePosition;
	    if(absPosition){
	      self.writer.context().beginDetachedBlock();
	      self.writer.context().moveTo(absPosition.x || 0, absPosition.y || 0);
	    }

	    if (node.stack) {
	      self.processVerticalContainer(node);
	    } else if (node.columns) {
	      self.processColumns(node);
	    } else if (node.ul) {
	      self.processList(false, node);
	    } else if (node.ol) {
	      self.processList(true, node);
	    } else if (node.table) {
	      self.processTable(node);
	    } else if (node.text !== undefined) {
	      self.processLeaf(node);
	    } else if (node.image) {
	      self.processImage(node);
	    } else if (node.canvas) {
	      self.processCanvas(node);
	    } else if (node.qr) {
	      self.processQr(node);
	    }else if (!node._span) {
			throw 'Unrecognized document structure: ' + JSON.stringify(node, fontStringify);
			}

	    if(absPosition){
	      self.writer.context().endDetachedBlock();
	    }
		});

		function applyMargins(callback) {
			var margin = node._margin;

	    if (node.pageBreak === 'before') {
	        self.writer.moveToNextPage(node.pageOrientation);
	    }

			if (margin) {
				self.writer.context().moveDown(margin[1]);
				self.writer.context().addMargin(margin[0], margin[2]);
			}

			callback();

			if(margin) {
				self.writer.context().addMargin(-margin[0], -margin[2]);
				self.writer.context().moveDown(margin[3]);
			}

	    if (node.pageBreak === 'after') {
	        self.writer.moveToNextPage(node.pageOrientation);
	    }
		}
	};

	// vertical container
	LayoutBuilder.prototype.processVerticalContainer = function(node) {
		var self = this;
		node.stack.forEach(function(item) {
			self.processNode(item);
			addAll(node.positions, item.positions);

			//TODO: paragraph gap
		});
	};

	// columns
	LayoutBuilder.prototype.processColumns = function(columnNode) {
		var columns = columnNode.columns;
		var availableWidth = this.writer.context().availableWidth;
		var gaps = gapArray(columnNode._gap);

		if (gaps) availableWidth -= (gaps.length - 1) * columnNode._gap;

		ColumnCalculator.buildColumnWidths(columns, availableWidth);
		var result = this.processRow(columns, columns, gaps);
	    addAll(columnNode.positions, result.positions);


		function gapArray(gap) {
			if (!gap) return null;

			var gaps = [];
			gaps.push(0);

			for(var i = columns.length - 1; i > 0; i--) {
				gaps.push(gap);
			}

			return gaps;
		}
	};

	LayoutBuilder.prototype.processRow = function(columns, widths, gaps, tableBody, tableRow) {
	  var self = this;
	  var pageBreaks = [], positions = [];

	  this.tracker.auto('pageChanged', storePageBreakData, function() {
	    widths = widths || columns;

	    self.writer.context().beginColumnGroup();

	    for(var i = 0, l = columns.length; i < l; i++) {
	      var column = columns[i];
	      var width = widths[i]._calcWidth;
	      var leftOffset = colLeftOffset(i);

	      if (column.colSpan && column.colSpan > 1) {
	          for(var j = 1; j < column.colSpan; j++) {
	              width += widths[++i]._calcWidth + gaps[i];
	          }
	      }

	      self.writer.context().beginColumn(width, leftOffset, getEndingCell(column, i));
	      if (!column._span) {
	        self.processNode(column);
	        addAll(positions, column.positions);
	      } else if (column._columnEndingContext) {
	        // row-span ending
	        self.writer.context().markEnding(column);
	      }
	    }

	    self.writer.context().completeColumnGroup();
	  });

	  return {pageBreaks: pageBreaks, positions: positions};

	  function storePageBreakData(data) {
	    var pageDesc;

	    for(var i = 0, l = pageBreaks.length; i < l; i++) {
	      var desc = pageBreaks[i];
	      if (desc.prevPage === data.prevPage) {
	        pageDesc = desc;
	        break;
	      }
	    }

	    if (!pageDesc) {
	      pageDesc = data;
	      pageBreaks.push(pageDesc);
	    }
	    pageDesc.prevY = Math.max(pageDesc.prevY, data.prevY);
	    pageDesc.y = Math.min(pageDesc.y, data.y);
	  }

		function colLeftOffset(i) {
			if (gaps && gaps.length > i) return gaps[i];
			return 0;
		}

	  function getEndingCell(column, columnIndex) {
	    if (column.rowSpan && column.rowSpan > 1) {
	      var endingRow = tableRow + column.rowSpan - 1;
	      if (endingRow >= tableBody.length) throw 'Row span for column ' + columnIndex + ' (with indexes starting from 0) exceeded row count';
	      return tableBody[endingRow][columnIndex];
	    }

	    return null;
	  }
	};

	// lists
	LayoutBuilder.prototype.processList = function(orderedList, node) {
		var self = this,
	      items = orderedList ? node.ol : node.ul,
	      gapSize = node._gapSize;

		this.writer.context().addMargin(gapSize.width);

		var nextMarker;
		this.tracker.auto('lineAdded', addMarkerToFirstLeaf, function() {
			items.forEach(function(item) {
				nextMarker = item.listMarker;
				self.processNode(item);
	            addAll(node.positions, item.positions);
			});
		});

		this.writer.context().addMargin(-gapSize.width);

		function addMarkerToFirstLeaf(line) {
			// I'm not very happy with the way list processing is implemented
			// (both code and algorithm should be rethinked)
			if (nextMarker) {
				var marker = nextMarker;
				nextMarker = null;

				if (marker.canvas) {
					var vector = marker.canvas[0];

					offsetVector(vector, -marker._minWidth, 0);
					self.writer.addVector(vector);
				} else {
					var markerLine = new Line(self.pageSize.width);
					markerLine.addInline(marker._inlines[0]);
					markerLine.x = -marker._minWidth;
					markerLine.y = line.getAscenderHeight() - markerLine.getAscenderHeight();
					self.writer.addLine(markerLine, true);
				}
			}
		}
	};

	// tables
	LayoutBuilder.prototype.processTable = function(tableNode) {
	  var processor = new TableProcessor(tableNode);

	  processor.beginTable(this.writer);

	  for(var i = 0, l = tableNode.table.body.length; i < l; i++) {
	    processor.beginRow(i, this.writer);

	    var result = this.processRow(tableNode.table.body[i], tableNode.table.widths, tableNode._offsets.offsets, tableNode.table.body, i);
	    addAll(tableNode.positions, result.positions);

	    processor.endRow(i, this.writer, result.pageBreaks);
	  }

	  processor.endTable(this.writer);
	};

	// leafs (texts)
	LayoutBuilder.prototype.processLeaf = function(node) {
		var line = this.buildNextLine(node);

		while (line) {
			var positions = this.writer.addLine(line);
	    node.positions.push(positions);
			line = this.buildNextLine(node);
		}
	};

	LayoutBuilder.prototype.buildNextLine = function(textNode) {
		if (!textNode._inlines || textNode._inlines.length === 0) return null;

		var line = new Line(this.writer.context().availableWidth);

		while(textNode._inlines && textNode._inlines.length > 0 && line.hasEnoughSpaceForInline(textNode._inlines[0])) {
			line.addInline(textNode._inlines.shift());
		}

		line.lastLineInParagraph = textNode._inlines.length === 0;
		return line;
	};

	// images
	LayoutBuilder.prototype.processImage = function(node) {
	    var position = this.writer.addImage(node);
	    node.positions.push(position);
	};

	LayoutBuilder.prototype.processCanvas = function(node) {
		var height = node._minHeight;

		if (this.writer.context().availableHeight < height) {
			// TODO: support for canvas larger than a page
			// TODO: support for other overflow methods

			this.writer.moveToNextPage();
		}

		node.canvas.forEach(function(vector) {
			var position = this.writer.addVector(vector);
	        node.positions.push(position);
		}, this);

		this.writer.context().moveDown(height);
	};

	LayoutBuilder.prototype.processQr = function(node) {
		var position = this.writer.addQr(node);
	    node.positions.push(position);
	};

	module.exports = LayoutBuilder;


/***/ },
/* 7 */
/***/ function(module, exports, __webpack_require__) {

	module.exports = {
		'4A0': [4767.87, 6740.79],
		'2A0': [3370.39, 4767.87],
		A0: [2383.94, 3370.39],
		A1: [1683.78, 2383.94],
		A2: [1190.55, 1683.78],
		A3: [841.89, 1190.55],
		A4: [595.28, 841.89],
		A5: [419.53, 595.28],
		A6: [297.64, 419.53],
		A7: [209.76, 297.64],
		A8: [147.40, 209.76],
		A9: [104.88, 147.40],
		A10: [73.70, 104.88],
		B0: [2834.65, 4008.19],
		B1: [2004.09, 2834.65],
		B2: [1417.32, 2004.09],
		B3: [1000.63, 1417.32],
		B4: [708.66, 1000.63],
		B5: [498.90, 708.66],
		B6: [354.33, 498.90],
		B7: [249.45, 354.33],
		B8: [175.75, 249.45],
		B9: [124.72, 175.75],
		B10: [87.87, 124.72],
		C0: [2599.37, 3676.54],
		C1: [1836.85, 2599.37],
		C2: [1298.27, 1836.85],
		C3: [918.43, 1298.27],
		C4: [649.13, 918.43],
		C5: [459.21, 649.13],
		C6: [323.15, 459.21],
		C7: [229.61, 323.15],
		C8: [161.57, 229.61],
		C9: [113.39, 161.57],
		C10: [79.37, 113.39],
		RA0: [2437.80, 3458.27],
		RA1: [1729.13, 2437.80],
		RA2: [1218.90, 1729.13],
		RA3: [864.57, 1218.90],
		RA4: [609.45, 864.57],
		SRA0: [2551.18, 3628.35],
		SRA1: [1814.17, 2551.18],
		SRA2: [1275.59, 1814.17],
		SRA3: [907.09, 1275.59],
		SRA4: [637.80, 907.09],
		EXECUTIVE: [521.86, 756.00],
		FOLIO: [612.00, 936.00],
		LEGAL: [612.00, 1008.00],
		LETTER: [612.00, 792.00],
		TABLOID: [792.00, 1224.00]
	};


/***/ },
/* 8 */
/***/ function(module, exports, __webpack_require__) {

	/* WEBPACK VAR INJECTION */(function(Buffer) {/* jslint node: true */
	'use strict';

	var pdfKit = __webpack_require__(28);
	var PDFImage = __webpack_require__(17);

	function ImageMeasure(pdfDoc, imageDictionary) {
		this.pdfDoc = pdfDoc;
		this.imageDictionary = imageDictionary || {};
	}

	ImageMeasure.prototype.measureImage = function(src) {
		var image, label;
		var that = this;

		if (!this.pdfDoc._imageRegistry[src]) {
			label = 'I' + (++this.pdfDoc._imageCount);
			image = PDFImage.open(realImageSrc(src), label);
			image.embed(this.pdfDoc);
			this.pdfDoc._imageRegistry[src] = image;
		} else {
			image = this.pdfDoc._imageRegistry[src];
		}

		return { width: image.width, height: image.height };

		function realImageSrc(src) {
			var img = that.imageDictionary[src];

			if (!img) return src;

			var index = img.indexOf('base64,');
			if (index < 0) {
				throw 'invalid image format, images dictionary should contain dataURL entries';
			}

			return new Buffer(img.substring(index + 7), 'base64');
		}
	};

	module.exports = ImageMeasure;

	/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(4).Buffer))

/***/ },
/* 9 */
/***/ function(module, exports, __webpack_require__) {

	/* jslint node: true */
	'use strict';


	function groupDecorations(line) {
		var groups = [], curGroup = null;
		for(var i = 0, l = line.inlines.length; i < l; i++) {
			var inline = line.inlines[i];
			var decoration = inline.decoration;
			if(!decoration) {
				curGroup = null;
				continue;
			}
			var color = inline.decorationColor || inline.color || 'black';
			var style = inline.decorationStyle || 'solid';
			decoration = Array.isArray(decoration) ? decoration : [ decoration ];
			for(var ii = 0, ll = decoration.length; ii < ll; ii++) {
				var deco = decoration[ii];
				if(!curGroup || deco !== curGroup.decoration ||
						style !== curGroup.decorationStyle || color !== curGroup.decorationColor ||
						deco === 'lineThrough') {
			
					curGroup = {
						line: line,
						decoration: deco, 
						decorationColor: color, 
						decorationStyle: style,
						inlines: [ inline ]
					};
					groups.push(curGroup);
				} else {
					curGroup.inlines.push(inline);
				}
			}
		}
		
		return groups;
	}

	function drawDecoration(group, x, y, pdfKitDoc) {
		function maxInline() {
			var max = 0;
			for (var i = 0, l = group.inlines.length; i < l; i++) {
				var inl = group.inlines[i];
				max = inl.fontSize > max ? i : max;
			}
			return group.inlines[max];
		}
		function width() {
			var sum = 0;
			for (var i = 0, l = group.inlines.length; i < l; i++) {
				sum += group.inlines[i].width;
			}
			return sum;
		}
		var firstInline = group.inlines[0],
			biggerInline = maxInline(),
			totalWidth = width(),
			lineAscent = group.line.getAscenderHeight(),
			ascent = biggerInline.font.ascender / 1000 * biggerInline.fontSize,
			height = biggerInline.height,
			descent = height - ascent;
		
		var lw = 0.5 + Math.floor(Math.max(biggerInline.fontSize - 8, 0) / 2) * 0.12;
		
		switch (group.decoration) {
			case 'underline':
				y += lineAscent + descent * 0.45;
				break;
			case 'overline':
				y += lineAscent - (ascent * 0.85);
				break;
			case 'lineThrough':
				y += lineAscent - (ascent * 0.25);
				break;
			default:
				throw 'Unkown decoration : ' + group.decoration;
		}
		pdfKitDoc.save();
		
		if(group.decorationStyle === 'double') {
			var gap = Math.max(0.5, lw*2);
			pdfKitDoc	.fillColor(group.decorationColor)
						.rect(x + firstInline.x, y-lw/2, totalWidth, lw/2).fill()
						.rect(x + firstInline.x, y+gap-lw/2, totalWidth, lw/2).fill();
		} else if(group.decorationStyle === 'dashed') {
			var nbDashes = Math.ceil(totalWidth / (3.96+2.84));
			var rdx = x + firstInline.x;
			pdfKitDoc.rect(rdx, y, totalWidth, lw).clip();
			pdfKitDoc.fillColor(group.decorationColor);
			for (var i = 0; i < nbDashes; i++) {
				pdfKitDoc.rect(rdx, y-lw/2, 3.96, lw).fill();
				rdx += 3.96 + 2.84;
			}
		} else if(group.decorationStyle === 'dotted') {
			var nbDots = Math.ceil(totalWidth / (lw*3));
			var rx = x + firstInline.x;
			pdfKitDoc.rect(rx, y, totalWidth, lw).clip();
			pdfKitDoc.fillColor(group.decorationColor);
			for (var ii = 0; ii < nbDots; ii++) {
				pdfKitDoc.rect(rx, y-lw/2, lw, lw).fill();
				rx += (lw*3);
			}
		} else if(group.decorationStyle === 'wavy') {
			var sh = 0.7, sv = 1;
			var nbWaves = Math.ceil(totalWidth / (sh*2))+1;
			var rwx = x + firstInline.x - 1;
			pdfKitDoc.rect(x + firstInline.x, y-sv, totalWidth, y+sv).clip();
			pdfKitDoc.lineWidth(0.24);
			pdfKitDoc.moveTo(rwx, y);
			for(var iii = 0; iii < nbWaves; iii++) {
				pdfKitDoc   .bezierCurveTo(rwx+sh, y-sv, rwx+sh*2, y-sv, rwx+sh*3, y)
							.bezierCurveTo(rwx+sh*4, y+sv, rwx+sh*5, y+sv, rwx+sh*6, y);
					rwx += sh*6;
				}
			pdfKitDoc.stroke(group.decorationColor);
			
		} else {
			pdfKitDoc	.fillColor(group.decorationColor)
						.rect(x + firstInline.x, y-lw/2, totalWidth, lw)
						.fill();
		}
		pdfKitDoc.restore();
	}

	function drawDecorations(line, x, y, pdfKitDoc) {
		var groups = groupDecorations(line);
		for (var i = 0, l = groups.length; i < l; i++) {
			drawDecoration(groups[i], x, y, pdfKitDoc);
		}
	}

	function drawBackground(line, x, y, pdfKitDoc) {
		var height = line.getHeight();
		for(var i = 0, l = line.inlines.length; i < l; i++) {
			var inline = line.inlines[i];
				if(inline.background) {
					pdfKitDoc	.fillColor(inline.background)
								.rect(x + inline.x, y, inline.width, height)
								.fill();
				}
		}
	}

	module.exports = {
		drawBackground: drawBackground,
		drawDecorations: drawDecorations
	};

/***/ },
/* 10 */
/***/ function(module, exports, __webpack_require__) {

	/* WEBPACK VAR INJECTION */(function(Buffer, __dirname) {/* jslint node: true */
	'use strict';

	// var b64 = require('./base64.js').base64DecToArr;
	function VirtualFileSystem() {
		this.fileSystem = {};
		this.baseSystem = {};
	}

	VirtualFileSystem.prototype.readFileSync = function(filename) {
		filename = fixFilename(filename);

		var base64content = this.baseSystem[filename];
		if (base64content) {
			return new Buffer(base64content, 'base64');
		}

		return this.fileSystem[filename];
	};

	VirtualFileSystem.prototype.writeFileSync = function(filename, content) {
		this.fileSystem[fixFilename(filename)] = content;
	};

	VirtualFileSystem.prototype.bindFS = function(data) {
		this.baseSystem = data;
	};


	function fixFilename(filename) {
		if (filename.indexOf(__dirname) === 0) {
			filename = filename.substring(__dirname.length);
		}

		if (filename.indexOf('/') === 0) {
			filename = filename.substring(1);
		}

		return filename;
	}

	module.exports = new VirtualFileSystem();

	/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(4).Buffer, "/"))

/***/ },
/* 11 */
/***/ function(module, exports, __webpack_require__) {

	var __WEBPACK_AMD_DEFINE_RESULT__;/* WEBPACK VAR INJECTION */(function(module, global) {/**
	 * @license
	 * lodash 3.1.0 (Custom Build) <https://lodash.com/>
	 * Build: `lodash modern -d -o ./index.js`
	 * Copyright 2012-2015 The Dojo Foundation <http://dojofoundation.org/>
	 * Based on Underscore.js 1.7.0 <http://underscorejs.org/LICENSE>
	 * Copyright 2009-2015 Jeremy Ashkenas, DocumentCloud and Investigative Reporters & Editors
	 * Available under MIT license <https://lodash.com/license>
	 */
	;(function() {

	  /** Used as a safe reference for `undefined` in pre-ES5 environments. */
	  var undefined;

	  /** Used as the semantic version number. */
	  var VERSION = '3.1.0';

	  /** Used to compose bitmasks for wrapper metadata. */
	  var BIND_FLAG = 1,
	      BIND_KEY_FLAG = 2,
	      CURRY_BOUND_FLAG = 4,
	      CURRY_FLAG = 8,
	      CURRY_RIGHT_FLAG = 16,
	      PARTIAL_FLAG = 32,
	      PARTIAL_RIGHT_FLAG = 64,
	      REARG_FLAG = 128,
	      ARY_FLAG = 256;

	  /** Used as default options for `_.trunc`. */
	  var DEFAULT_TRUNC_LENGTH = 30,
	      DEFAULT_TRUNC_OMISSION = '...';

	  /** Used to detect when a function becomes hot. */
	  var HOT_COUNT = 150,
	      HOT_SPAN = 16;

	  /** Used to indicate the type of lazy iteratees. */
	  var LAZY_FILTER_FLAG = 0,
	      LAZY_MAP_FLAG = 1,
	      LAZY_WHILE_FLAG = 2;

	  /** Used as the `TypeError` message for "Functions" methods. */
	  var FUNC_ERROR_TEXT = 'Expected a function';

	  /** Used as the internal argument placeholder. */
	  var PLACEHOLDER = '__lodash_placeholder__';

	  /** `Object#toString` result references. */
	  var argsTag = '[object Arguments]',
	      arrayTag = '[object Array]',
	      boolTag = '[object Boolean]',
	      dateTag = '[object Date]',
	      errorTag = '[object Error]',
	      funcTag = '[object Function]',
	      mapTag = '[object Map]',
	      numberTag = '[object Number]',
	      objectTag = '[object Object]',
	      regexpTag = '[object RegExp]',
	      setTag = '[object Set]',
	      stringTag = '[object String]',
	      weakMapTag = '[object WeakMap]';

	  var arrayBufferTag = '[object ArrayBuffer]',
	      float32Tag = '[object Float32Array]',
	      float64Tag = '[object Float64Array]',
	      int8Tag = '[object Int8Array]',
	      int16Tag = '[object Int16Array]',
	      int32Tag = '[object Int32Array]',
	      uint8Tag = '[object Uint8Array]',
	      uint8ClampedTag = '[object Uint8ClampedArray]',
	      uint16Tag = '[object Uint16Array]',
	      uint32Tag = '[object Uint32Array]';

	  /** Used to match empty string literals in compiled template source. */
	  var reEmptyStringLeading = /\b__p \+= '';/g,
	      reEmptyStringMiddle = /\b(__p \+=) '' \+/g,
	      reEmptyStringTrailing = /(__e\(.*?\)|\b__t\)) \+\n'';/g;

	  /** Used to match HTML entities and HTML characters. */
	  var reEscapedHtml = /&(?:amp|lt|gt|quot|#39|#96);/g,
	      reUnescapedHtml = /[&<>"'`]/g,
	      reHasEscapedHtml = RegExp(reEscapedHtml.source),
	      reHasUnescapedHtml = RegExp(reUnescapedHtml.source);

	  /** Used to match template delimiters. */
	  var reEscape = /<%-([\s\S]+?)%>/g,
	      reEvaluate = /<%([\s\S]+?)%>/g,
	      reInterpolate = /<%=([\s\S]+?)%>/g;

	  /**
	   * Used to match ES template delimiters.
	   * See the [ES spec](https://people.mozilla.org/~jorendorff/es6-draft.html#sec-template-literal-lexical-components)
	   * for more details.
	   */
	  var reEsTemplate = /\$\{([^\\}]*(?:\\.[^\\}]*)*)\}/g;

	  /** Used to match `RegExp` flags from their coerced string values. */
	  var reFlags = /\w*$/;

	  /** Used to detect named functions. */
	  var reFuncName = /^\s*function[ \n\r\t]+\w/;

	  /** Used to detect hexadecimal string values. */
	  var reHexPrefix = /^0[xX]/;

	  /** Used to detect host constructors (Safari > 5). */
	  var reHostCtor = /^\[object .+?Constructor\]$/;

	  /** Used to match latin-1 supplementary letters (excluding mathematical operators). */
	  var reLatin1 = /[\xc0-\xd6\xd8-\xde\xdf-\xf6\xf8-\xff]/g;

	  /** Used to ensure capturing order of template delimiters. */
	  var reNoMatch = /($^)/;

	  /**
	   * Used to match `RegExp` special characters.
	   * See this [article on `RegExp` characters](http://www.regular-expressions.info/characters.html#special)
	   * for more details.
	   */
	  var reRegExpChars = /[.*+?^${}()|[\]\/\\]/g,
	      reHasRegExpChars = RegExp(reRegExpChars.source);

	  /** Used to detect functions containing a `this` reference. */
	  var reThis = /\bthis\b/;

	  /** Used to match unescaped characters in compiled string literals. */
	  var reUnescapedString = /['\n\r\u2028\u2029\\]/g;

	  /** Used to match words to create compound words. */
	  var reWords = (function() {
	    var upper = '[A-Z\\xc0-\\xd6\\xd8-\\xde]',
	        lower = '[a-z\\xdf-\\xf6\\xf8-\\xff]+';

	    return RegExp(upper + '{2,}(?=' + upper + lower + ')|' + upper + '?' + lower + '|' + upper + '+|[0-9]+', 'g');
	  }());

	  /** Used to detect and test for whitespace. */
	  var whitespace = (
	    // Basic whitespace characters.
	    ' \t\x0b\f\xa0\ufeff' +

	    // Line terminators.
	    '\n\r\u2028\u2029' +

	    // Unicode category "Zs" space separators.
	    '\u1680\u180e\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u202f\u205f\u3000'
	  );

	  /** Used to assign default `context` object properties. */
	  var contextProps = [
	    'Array', 'ArrayBuffer', 'Date', 'Error', 'Float32Array', 'Float64Array',
	    'Function', 'Int8Array', 'Int16Array', 'Int32Array', 'Math', 'Number',
	    'Object', 'RegExp', 'Set', 'String', '_', 'clearTimeout', 'document',
	    'isFinite', 'parseInt', 'setTimeout', 'TypeError', 'Uint8Array',
	    'Uint8ClampedArray', 'Uint16Array', 'Uint32Array', 'WeakMap',
	    'window', 'WinRTError'
	  ];

	  /** Used to make template sourceURLs easier to identify. */
	  var templateCounter = -1;

	  /** Used to identify `toStringTag` values of typed arrays. */
	  var typedArrayTags = {};
	  typedArrayTags[float32Tag] = typedArrayTags[float64Tag] =
	  typedArrayTags[int8Tag] = typedArrayTags[int16Tag] =
	  typedArrayTags[int32Tag] = typedArrayTags[uint8Tag] =
	  typedArrayTags[uint8ClampedTag] = typedArrayTags[uint16Tag] =
	  typedArrayTags[uint32Tag] = true;
	  typedArrayTags[argsTag] = typedArrayTags[arrayTag] =
	  typedArrayTags[arrayBufferTag] = typedArrayTags[boolTag] =
	  typedArrayTags[dateTag] = typedArrayTags[errorTag] =
	  typedArrayTags[funcTag] = typedArrayTags[mapTag] =
	  typedArrayTags[numberTag] = typedArrayTags[objectTag] =
	  typedArrayTags[regexpTag] = typedArrayTags[setTag] =
	  typedArrayTags[stringTag] = typedArrayTags[weakMapTag] = false;

	  /** Used to identify `toStringTag` values supported by `_.clone`. */
	  var cloneableTags = {};
	  cloneableTags[argsTag] = cloneableTags[arrayTag] =
	  cloneableTags[arrayBufferTag] = cloneableTags[boolTag] =
	  cloneableTags[dateTag] = cloneableTags[float32Tag] =
	  cloneableTags[float64Tag] = cloneableTags[int8Tag] =
	  cloneableTags[int16Tag] = cloneableTags[int32Tag] =
	  cloneableTags[numberTag] = cloneableTags[objectTag] =
	  cloneableTags[regexpTag] = cloneableTags[stringTag] =
	  cloneableTags[uint8Tag] = cloneableTags[uint8ClampedTag] =
	  cloneableTags[uint16Tag] = cloneableTags[uint32Tag] = true;
	  cloneableTags[errorTag] = cloneableTags[funcTag] =
	  cloneableTags[mapTag] = cloneableTags[setTag] =
	  cloneableTags[weakMapTag] = false;

	  /** Used as an internal `_.debounce` options object by `_.throttle`. */
	  var debounceOptions = {
	    'leading': false,
	    'maxWait': 0,
	    'trailing': false
	  };

	  /** Used to map latin-1 supplementary letters to basic latin letters. */
	  var deburredLetters = {
	    '\xc0': 'A',  '\xc1': 'A', '\xc2': 'A', '\xc3': 'A', '\xc4': 'A', '\xc5': 'A',
	    '\xe0': 'a',  '\xe1': 'a', '\xe2': 'a', '\xe3': 'a', '\xe4': 'a', '\xe5': 'a',
	    '\xc7': 'C',  '\xe7': 'c',
	    '\xd0': 'D',  '\xf0': 'd',
	    '\xc8': 'E',  '\xc9': 'E', '\xca': 'E', '\xcb': 'E',
	    '\xe8': 'e',  '\xe9': 'e', '\xea': 'e', '\xeb': 'e',
	    '\xcC': 'I',  '\xcd': 'I', '\xce': 'I', '\xcf': 'I',
	    '\xeC': 'i',  '\xed': 'i', '\xee': 'i', '\xef': 'i',
	    '\xd1': 'N',  '\xf1': 'n',
	    '\xd2': 'O',  '\xd3': 'O', '\xd4': 'O', '\xd5': 'O', '\xd6': 'O', '\xd8': 'O',
	    '\xf2': 'o',  '\xf3': 'o', '\xf4': 'o', '\xf5': 'o', '\xf6': 'o', '\xf8': 'o',
	    '\xd9': 'U',  '\xda': 'U', '\xdb': 'U', '\xdc': 'U',
	    '\xf9': 'u',  '\xfa': 'u', '\xfb': 'u', '\xfc': 'u',
	    '\xdd': 'Y',  '\xfd': 'y', '\xff': 'y',
	    '\xc6': 'Ae', '\xe6': 'ae',
	    '\xde': 'Th', '\xfe': 'th',
	    '\xdf': 'ss'
	  };

	  /** Used to map characters to HTML entities. */
	  var htmlEscapes = {
	    '&': '&amp;',
	    '<': '&lt;',
	    '>': '&gt;',
	    '"': '&quot;',
	    "'": '&#39;',
	    '`': '&#96;'
	  };

	  /** Used to map HTML entities to characters. */
	  var htmlUnescapes = {
	    '&amp;': '&',
	    '&lt;': '<',
	    '&gt;': '>',
	    '&quot;': '"',
	    '&#39;': "'",
	    '&#96;': '`'
	  };

	  /** Used to determine if values are of the language type `Object`. */
	  var objectTypes = {
	    'function': true,
	    'object': true
	  };

	  /** Used to escape characters for inclusion in compiled string literals. */
	  var stringEscapes = {
	    '\\': '\\',
	    "'": "'",
	    '\n': 'n',
	    '\r': 'r',
	    '\u2028': 'u2028',
	    '\u2029': 'u2029'
	  };

	  /**
	   * Used as a reference to the global object.
	   *
	   * The `this` value is used if it is the global object to avoid Greasemonkey's
	   * restricted `window` object, otherwise the `window` object is used.
	   */
	  var root = (objectTypes[typeof window] && window !== (this && this.window)) ? window : this;

	  /** Detect free variable `exports`. */
	  var freeExports = objectTypes[typeof exports] && exports && !exports.nodeType && exports;

	  /** Detect free variable `module`. */
	  var freeModule = objectTypes[typeof module] && module && !module.nodeType && module;

	  /** Detect free variable `global` from Node.js or Browserified code and use it as `root`. */
	  var freeGlobal = freeExports && freeModule && typeof global == 'object' && global;
	  if (freeGlobal && (freeGlobal.global === freeGlobal || freeGlobal.window === freeGlobal || freeGlobal.self === freeGlobal)) {
	    root = freeGlobal;
	  }

	  /** Detect the popular CommonJS extension `module.exports`. */
	  var moduleExports = freeModule && freeModule.exports === freeExports && freeExports;

	  /*--------------------------------------------------------------------------*/

	  /**
	   * The base implementation of `compareAscending` which compares values and
	   * sorts them in ascending order without guaranteeing a stable sort.
	   *
	   * @private
	   * @param {*} value The value to compare to `other`.
	   * @param {*} other The value to compare to `value`.
	   * @returns {number} Returns the sort order indicator for `value`.
	   */
	  function baseCompareAscending(value, other) {
	    if (value !== other) {
	      var valIsReflexive = value === value,
	          othIsReflexive = other === other;

	      if (value > other || !valIsReflexive || (typeof value == 'undefined' && othIsReflexive)) {
	        return 1;
	      }
	      if (value < other || !othIsReflexive || (typeof other == 'undefined' && valIsReflexive)) {
	        return -1;
	      }
	    }
	    return 0;
	  }

	  /**
	   * The base implementation of `_.indexOf` without support for binary searches.
	   *
	   * @private
	   * @param {Array} array The array to search.
	   * @param {*} value The value to search for.
	   * @param {number} [fromIndex=0] The index to search from.
	   * @returns {number} Returns the index of the matched value, else `-1`.
	   */
	  function baseIndexOf(array, value, fromIndex) {
	    if (value !== value) {
	      return indexOfNaN(array, fromIndex);
	    }
	    var index = (fromIndex || 0) - 1,
	        length = array.length;

	    while (++index < length) {
	      if (array[index] === value) {
	        return index;
	      }
	    }
	    return -1;
	  }

	  /**
	   * The base implementation of `_.sortBy` and `_.sortByAll` which uses `comparer`
	   * to define the sort order of `array` and replaces criteria objects with their
	   * corresponding values.
	   *
	   * @private
	   * @param {Array} array The array to sort.
	   * @param {Function} comparer The function to define sort order.
	   * @returns {Array} Returns `array`.
	   */
	  function baseSortBy(array, comparer) {
	    var length = array.length;

	    array.sort(comparer);
	    while (length--) {
	      array[length] = array[length].value;
	    }
	    return array;
	  }

	  /**
	   * Converts `value` to a string if it is not one. An empty string is returned
	   * for `null` or `undefined` values.
	   *
	   * @private
	   * @param {*} value The value to process.
	   * @returns {string} Returns the string.
	   */
	  function baseToString(value) {
	    if (typeof value == 'string') {
	      return value;
	    }
	    return value == null ? '' : (value + '');
	  }

	  /**
	   * Used by `_.max` and `_.min` as the default callback for string values.
	   *
	   * @private
	   * @param {string} string The string to inspect.
	   * @returns {number} Returns the code unit of the first character of the string.
	   */
	  function charAtCallback(string) {
	    return string.charCodeAt(0);
	  }

	  /**
	   * Used by `_.trim` and `_.trimLeft` to get the index of the first character
	   * of `string` that is not found in `chars`.
	   *
	   * @private
	   * @param {string} string The string to inspect.
	   * @param {string} chars The characters to find.
	   * @returns {number} Returns the index of the first character not found in `chars`.
	   */
	  function charsLeftIndex(string, chars) {
	    var index = -1,
	        length = string.length;

	    while (++index < length && chars.indexOf(string.charAt(index)) > -1) {}
	    return index;
	  }

	  /**
	   * Used by `_.trim` and `_.trimRight` to get the index of the last character
	   * of `string` that is not found in `chars`.
	   *
	   * @private
	   * @param {string} string The string to inspect.
	   * @param {string} chars The characters to find.
	   * @returns {number} Returns the index of the last character not found in `chars`.
	   */
	  function charsRightIndex(string, chars) {
	    var index = string.length;

	    while (index-- && chars.indexOf(string.charAt(index)) > -1) {}
	    return index;
	  }

	  /**
	   * Used by `_.sortBy` to compare transformed elements of a collection and stable
	   * sort them in ascending order.
	   *
	   * @private
	   * @param {Object} object The object to compare to `other`.
	   * @param {Object} other The object to compare to `object`.
	   * @returns {number} Returns the sort order indicator for `object`.
	   */
	  function compareAscending(object, other) {
	    return baseCompareAscending(object.criteria, other.criteria) || (object.index - other.index);
	  }

	  /**
	   * Used by `_.sortByAll` to compare multiple properties of each element
	   * in a collection and stable sort them in ascending order.
	   *
	   * @private
	   * @param {Object} object The object to compare to `other`.
	   * @param {Object} other The object to compare to `object`.
	   * @returns {number} Returns the sort order indicator for `object`.
	   */
	  function compareMultipleAscending(object, other) {
	    var index = -1,
	        objCriteria = object.criteria,
	        othCriteria = other.criteria,
	        length = objCriteria.length;

	    while (++index < length) {
	      var result = baseCompareAscending(objCriteria[index], othCriteria[index]);
	      if (result) {
	        return result;
	      }
	    }
	    // Fixes an `Array#sort` bug in the JS engine embedded in Adobe applications
	    // that causes it, under certain circumstances, to provide the same value for
	    // `object` and `other`. See https://github.com/jashkenas/underscore/pull/1247
	    // for more details.
	    //
	    // This also ensures a stable sort in V8 and other engines.
	    // See https://code.google.com/p/v8/issues/detail?id=90 for more details.
	    return object.index - other.index;
	  }

	  /**
	   * Used by `_.deburr` to convert latin-1 supplementary letters to basic latin letters.
	   *
	   * @private
	   * @param {string} letter The matched letter to deburr.
	   * @returns {string} Returns the deburred letter.
	   */
	  function deburrLetter(letter) {
	    return deburredLetters[letter];
	  }

	  /**
	   * Used by `_.escape` to convert characters to HTML entities.
	   *
	   * @private
	   * @param {string} chr The matched character to escape.
	   * @returns {string} Returns the escaped character.
	   */
	  function escapeHtmlChar(chr) {
	    return htmlEscapes[chr];
	  }

	  /**
	   * Used by `_.template` to escape characters for inclusion in compiled
	   * string literals.
	   *
	   * @private
	   * @param {string} chr The matched character to escape.
	   * @returns {string} Returns the escaped character.
	   */
	  function escapeStringChar(chr) {
	    return '\\' + stringEscapes[chr];
	  }

	  /**
	   * Gets the index at which the first occurrence of `NaN` is found in `array`.
	   * If `fromRight` is provided elements of `array` are iterated from right to left.
	   *
	   * @private
	   * @param {Array} array The array to search.
	   * @param {number} [fromIndex] The index to search from.
	   * @param {boolean} [fromRight] Specify iterating from right to left.
	   * @returns {number} Returns the index of the matched `NaN`, else `-1`.
	   */
	  function indexOfNaN(array, fromIndex, fromRight) {
	    var length = array.length,
	        index = fromRight ? (fromIndex || length) : ((fromIndex || 0) - 1);

	    while ((fromRight ? index-- : ++index < length)) {
	      var other = array[index];
	      if (other !== other) {
	        return index;
	      }
	    }
	    return -1;
	  }

	  /**
	   * Checks if `value` is object-like.
	   *
	   * @private
	   * @param {*} value The value to check.
	   * @returns {boolean} Returns `true` if `value` is object-like, else `false`.
	   */
	  function isObjectLike(value) {
	    return (value && typeof value == 'object') || false;
	  }

	  /**
	   * Used by `trimmedLeftIndex` and `trimmedRightIndex` to determine if a
	   * character code is whitespace.
	   *
	   * @private
	   * @param {number} charCode The character code to inspect.
	   * @returns {boolean} Returns `true` if `charCode` is whitespace, else `false`.
	   */
	  function isSpace(charCode) {
	    return ((charCode <= 160 && (charCode >= 9 && charCode <= 13) || charCode == 32 || charCode == 160) || charCode == 5760 || charCode == 6158 ||
	      (charCode >= 8192 && (charCode <= 8202 || charCode == 8232 || charCode == 8233 || charCode == 8239 || charCode == 8287 || charCode == 12288 || charCode == 65279)));
	  }

	  /**
	   * Replaces all `placeholder` elements in `array` with an internal placeholder
	   * and returns an array of their indexes.
	   *
	   * @private
	   * @param {Array} array The array to modify.
	   * @param {*} placeholder The placeholder to replace.
	   * @returns {Array} Returns the new array of placeholder indexes.
	   */
	  function replaceHolders(array, placeholder) {
	    var index = -1,
	        length = array.length,
	        resIndex = -1,
	        result = [];

	    while (++index < length) {
	      if (array[index] === placeholder) {
	        array[index] = PLACEHOLDER;
	        result[++resIndex] = index;
	      }
	    }
	    return result;
	  }

	  /**
	   * An implementation of `_.uniq` optimized for sorted arrays without support
	   * for callback shorthands and `this` binding.
	   *
	   * @private
	   * @param {Array} array The array to inspect.
	   * @param {Function} [iteratee] The function invoked per iteration.
	   * @returns {Array} Returns the new duplicate-value-free array.
	   */
	  function sortedUniq(array, iteratee) {
	    var seen,
	        index = -1,
	        length = array.length,
	        resIndex = -1,
	        result = [];

	    while (++index < length) {
	      var value = array[index],
	          computed = iteratee ? iteratee(value, index, array) : value;

	      if (!index || seen !== computed) {
	        seen = computed;
	        result[++resIndex] = value;
	      }
	    }
	    return result;
	  }

	  /**
	   * Used by `_.trim` and `_.trimLeft` to get the index of the first non-whitespace
	   * character of `string`.
	   *
	   * @private
	   * @param {string} string The string to inspect.
	   * @returns {number} Returns the index of the first non-whitespace character.
	   */
	  function trimmedLeftIndex(string) {
	    var index = -1,
	        length = string.length;

	    while (++index < length && isSpace(string.charCodeAt(index))) {}
	    return index;
	  }

	  /**
	   * Used by `_.trim` and `_.trimRight` to get the index of the last non-whitespace
	   * character of `string`.
	   *
	   * @private
	   * @param {string} string The string to inspect.
	   * @returns {number} Returns the index of the last non-whitespace character.
	   */
	  function trimmedRightIndex(string) {
	    var index = string.length;

	    while (index-- && isSpace(string.charCodeAt(index))) {}
	    return index;
	  }

	  /**
	   * Used by `_.unescape` to convert HTML entities to characters.
	   *
	   * @private
	   * @param {string} chr The matched character to unescape.
	   * @returns {string} Returns the unescaped character.
	   */
	  function unescapeHtmlChar(chr) {
	    return htmlUnescapes[chr];
	  }

	  /*--------------------------------------------------------------------------*/

	  /**
	   * Create a new pristine `lodash` function using the given `context` object.
	   *
	   * @static
	   * @memberOf _
	   * @category Utility
	   * @param {Object} [context=root] The context object.
	   * @returns {Function} Returns a new `lodash` function.
	   * @example
	   *
	   * _.mixin({ 'add': function(a, b) { return a + b; } });
	   *
	   * var lodash = _.runInContext();
	   * lodash.mixin({ 'sub': function(a, b) { return a - b; } });
	   *
	   * _.isFunction(_.add);
	   * // => true
	   * _.isFunction(_.sub);
	   * // => false
	   *
	   * lodash.isFunction(lodash.add);
	   * // => false
	   * lodash.isFunction(lodash.sub);
	   * // => true
	   *
	   * // using `context` to mock `Date#getTime` use in `_.now`
	   * var mock = _.runInContext({
	   *   'Date': function() {
	   *     return { 'getTime': getTimeMock };
	   *   }
	   * });
	   *
	   * // or creating a suped-up `defer` in Node.js
	   * var defer = _.runInContext({ 'setTimeout': setImmediate }).defer;
	   */
	  function runInContext(context) {
	    // Avoid issues with some ES3 environments that attempt to use values, named
	    // after built-in constructors like `Object`, for the creation of literals.
	    // ES5 clears this up by stating that literals must use built-in constructors.
	    // See https://es5.github.io/#x11.1.5 for more details.
	    context = context ? _.defaults(root.Object(), context, _.pick(root, contextProps)) : root;

	    /** Native constructor references. */
	    var Array = context.Array,
	        Date = context.Date,
	        Error = context.Error,
	        Function = context.Function,
	        Math = context.Math,
	        Number = context.Number,
	        Object = context.Object,
	        RegExp = context.RegExp,
	        String = context.String,
	        TypeError = context.TypeError;

	    /** Used for native method references. */
	    var arrayProto = Array.prototype,
	        objectProto = Object.prototype;

	    /** Used to detect DOM support. */
	    var document = (document = context.window) && document.document;

	    /** Used to resolve the decompiled source of functions. */
	    var fnToString = Function.prototype.toString;

	    /** Used to the length of n-tuples for `_.unzip`. */
	    var getLength = baseProperty('length');

	    /** Used to check objects for own properties. */
	    var hasOwnProperty = objectProto.hasOwnProperty;

	    /** Used to generate unique IDs. */
	    var idCounter = 0;

	    /**
	     * Used to resolve the `toStringTag` of values.
	     * See the [ES spec](https://people.mozilla.org/~jorendorff/es6-draft.html#sec-object.prototype.tostring)
	     * for more details.
	     */
	    var objToString = objectProto.toString;

	    /** Used to restore the original `_` reference in `_.noConflict`. */
	    var oldDash = context._;

	    /** Used to detect if a method is native. */
	    var reNative = RegExp('^' +
	      escapeRegExp(objToString)
	      .replace(/toString|(function).*?(?=\\\()| for .+?(?=\\\])/g, '$1.*?') + '$'
	    );

	    /** Native method references. */
	    var ArrayBuffer = isNative(ArrayBuffer = context.ArrayBuffer) && ArrayBuffer,
	        bufferSlice = isNative(bufferSlice = ArrayBuffer && new ArrayBuffer(0).slice) && bufferSlice,
	        ceil = Math.ceil,
	        clearTimeout = context.clearTimeout,
	        floor = Math.floor,
	        getPrototypeOf = isNative(getPrototypeOf = Object.getPrototypeOf) && getPrototypeOf,
	        push = arrayProto.push,
	        propertyIsEnumerable = objectProto.propertyIsEnumerable,
	        Set = isNative(Set = context.Set) && Set,
	        setTimeout = context.setTimeout,
	        splice = arrayProto.splice,
	        Uint8Array = isNative(Uint8Array = context.Uint8Array) && Uint8Array,
	        unshift = arrayProto.unshift,
	        WeakMap = isNative(WeakMap = context.WeakMap) && WeakMap;

	    /** Used to clone array buffers. */
	    var Float64Array = (function() {
	      // Safari 5 errors when using an array buffer to initialize a typed array
	      // where the array buffer's `byteLength` is not a multiple of the typed
	      // array's `BYTES_PER_ELEMENT`.
	      try {
	        var func = isNative(func = context.Float64Array) && func,
	            result = new func(new ArrayBuffer(10), 0, 1) && func;
	      } catch(e) {}
	      return result;
	    }());

	    /* Native method references for those with the same name as other `lodash` methods. */
	    var nativeIsArray = isNative(nativeIsArray = Array.isArray) && nativeIsArray,
	        nativeCreate = isNative(nativeCreate = Object.create) && nativeCreate,
	        nativeIsFinite = context.isFinite,
	        nativeKeys = isNative(nativeKeys = Object.keys) && nativeKeys,
	        nativeMax = Math.max,
	        nativeMin = Math.min,
	        nativeNow = isNative(nativeNow = Date.now) && nativeNow,
	        nativeNumIsFinite = isNative(nativeNumIsFinite = Number.isFinite) && nativeNumIsFinite,
	        nativeParseInt = context.parseInt,
	        nativeRandom = Math.random;

	    /** Used as references for `-Infinity` and `Infinity`. */
	    var NEGATIVE_INFINITY = Number.NEGATIVE_INFINITY,
	        POSITIVE_INFINITY = Number.POSITIVE_INFINITY;

	    /** Used as references for the maximum length and index of an array. */
	    var MAX_ARRAY_LENGTH = Math.pow(2, 32) - 1,
	        MAX_ARRAY_INDEX =  MAX_ARRAY_LENGTH - 1,
	        HALF_MAX_ARRAY_LENGTH = MAX_ARRAY_LENGTH >>> 1;

	    /** Used as the size, in bytes, of each `Float64Array` element. */
	    var FLOAT64_BYTES_PER_ELEMENT = Float64Array ? Float64Array.BYTES_PER_ELEMENT : 0;

	    /**
	     * Used as the maximum length of an array-like value.
	     * See the [ES spec](https://people.mozilla.org/~jorendorff/es6-draft.html#sec-number.max_safe_integer)
	     * for more details.
	     */
	    var MAX_SAFE_INTEGER = Math.pow(2, 53) - 1;

	    /** Used to store function metadata. */
	    var metaMap = WeakMap && new WeakMap;

	    /*------------------------------------------------------------------------*/

	    /**
	     * Creates a `lodash` object which wraps `value` to enable intuitive chaining.
	     * Methods that operate on and return arrays, collections, and functions can
	     * be chained together. Methods that return a boolean or single value will
	     * automatically end the chain returning the unwrapped value. Explicit chaining
	     * may be enabled using `_.chain`. The execution of chained methods is lazy,
	     * that is, execution is deferred until `_#value` is implicitly or explicitly
	     * called.
	     *
	     * Lazy evaluation allows several methods to support shortcut fusion. Shortcut
	     * fusion is an optimization that merges iteratees to avoid creating intermediate
	     * arrays and reduce the number of iteratee executions.
	     *
	     * Chaining is supported in custom builds as long as the `_#value` method is
	     * directly or indirectly included in the build.
	     *
	     * In addition to lodash methods, wrappers also have the following `Array` methods:
	     * `concat`, `join`, `pop`, `push`, `reverse`, `shift`, `slice`, `sort`, `splice`,
	     * and `unshift`
	     *
	     * The wrapper functions that support shortcut fusion are:
	     * `drop`, `dropRight`, `dropRightWhile`, `dropWhile`, `filter`, `first`,
	     * `initial`, `last`, `map`, `pluck`, `reject`, `rest`, `reverse`, `slice`,
	     * `take`, `takeRight`, `takeRightWhile`, `takeWhile`, and `where`
	     *
	     * The chainable wrapper functions are:
	     * `after`, `ary`, `assign`, `at`, `before`, `bind`, `bindAll`, `bindKey`,
	     * `callback`, `chain`, `chunk`, `compact`, `concat`, `constant`, `countBy`,
	     * `create`, `curry`, `debounce`, `defaults`, `defer`, `delay`, `difference`,
	     * `drop`, `dropRight`, `dropRightWhile`, `dropWhile`, `filter`, `flatten`,
	     * `flattenDeep`, `flow`, `flowRight`, `forEach`, `forEachRight`, `forIn`,
	     * `forInRight`, `forOwn`, `forOwnRight`, `functions`, `groupBy`, `indexBy`,
	     * `initial`, `intersection`, `invert`, `invoke`, `keys`, `keysIn`, `map`,
	     * `mapValues`, `matches`, `memoize`, `merge`, `mixin`, `negate`, `noop`,
	     * `omit`, `once`, `pairs`, `partial`, `partialRight`, `partition`, `pick`,
	     * `pluck`, `property`, `propertyOf`, `pull`, `pullAt`, `push`, `range`,
	     * `rearg`, `reject`, `remove`, `rest`, `reverse`, `shuffle`, `slice`, `sort`,
	     * `sortBy`, `sortByAll`, `splice`, `take`, `takeRight`, `takeRightWhile`,
	     * `takeWhile`, `tap`, `throttle`, `thru`, `times`, `toArray`, `toPlainObject`,
	     * `transform`, `union`, `uniq`, `unshift`, `unzip`, `values`, `valuesIn`,
	     * `where`, `without`, `wrap`, `xor`, `zip`, and `zipObject`
	     *
	     * The wrapper functions that are **not** chainable by default are:
	     * `attempt`, `camelCase`, `capitalize`, `clone`, `cloneDeep`, `deburr`,
	     * `endsWith`, `escape`, `escapeRegExp`, `every`, `find`, `findIndex`, `findKey`,
	     * `findLast`, `findLastIndex`, `findLastKey`, `findWhere`, `first`, `has`,
	     * `identity`, `includes`, `indexOf`, `isArguments`, `isArray`, `isBoolean`,
	     * `isDate`, `isElement`, `isEmpty`, `isEqual`, `isError`, `isFinite`,
	     * `isFunction`, `isMatch`, `isNative`, `isNaN`, `isNull`, `isNumber`,
	     * `isObject`, `isPlainObject`, `isRegExp`, `isString`, `isUndefined`,
	     * `isTypedArray`, `join`, `kebabCase`, `last`, `lastIndexOf`, `max`, `min`,
	     * `noConflict`, `now`, `pad`, `padLeft`, `padRight`, `parseInt`, `pop`,
	     * `random`, `reduce`, `reduceRight`, `repeat`, `result`, `runInContext`,
	     * `shift`, `size`, `snakeCase`, `some`, `sortedIndex`, `sortedLastIndex`,
	     * `startCase`, `startsWith`, `template`, `trim`, `trimLeft`, `trimRight`,
	     * `trunc`, `unescape`, `uniqueId`, `value`, and `words`
	     *
	     * The wrapper function `sample` will return a wrapped value when `n` is provided,
	     * otherwise an unwrapped value is returned.
	     *
	     * @name _
	     * @constructor
	     * @category Chain
	     * @param {*} value The value to wrap in a `lodash` instance.
	     * @returns {Object} Returns a `lodash` instance.
	     * @example
	     *
	     * var wrapped = _([1, 2, 3]);
	     *
	     * // returns an unwrapped value
	     * wrapped.reduce(function(sum, n) { return sum + n; });
	     * // => 6
	     *
	     * // returns a wrapped value
	     * var squares = wrapped.map(function(n) { return n * n; });
	     *
	     * _.isArray(squares);
	     * // => false
	     *
	     * _.isArray(squares.value());
	     * // => true
	     */
	    function lodash(value) {
	      if (isObjectLike(value) && !isArray(value)) {
	        if (value instanceof LodashWrapper) {
	          return value;
	        }
	        if (hasOwnProperty.call(value, '__wrapped__')) {
	          return new LodashWrapper(value.__wrapped__, value.__chain__, arrayCopy(value.__actions__));
	        }
	      }
	      return new LodashWrapper(value);
	    }

	    /**
	     * The base constructor for creating `lodash` wrapper objects.
	     *
	     * @private
	     * @param {*} value The value to wrap.
	     * @param {boolean} [chainAll] Enable chaining for all wrapper methods.
	     * @param {Array} [actions=[]] Actions to peform to resolve the unwrapped value.
	     */
	    function LodashWrapper(value, chainAll, actions) {
	      this.__actions__ = actions || [];
	      this.__chain__ = !!chainAll;
	      this.__wrapped__ = value;
	    }

	    /**
	     * An object environment feature flags.
	     *
	     * @static
	     * @memberOf _
	     * @type Object
	     */
	    var support = lodash.support = {};

	    (function(x) {

	      /**
	       * Detect if functions can be decompiled by `Function#toString`
	       * (all but Firefox OS certified apps, older Opera mobile browsers, and
	       * the PlayStation 3; forced `false` for Windows 8 apps).
	       *
	       * @memberOf _.support
	       * @type boolean
	       */
	      support.funcDecomp = !isNative(context.WinRTError) && reThis.test(runInContext);

	      /**
	       * Detect if `Function#name` is supported (all but IE).
	       *
	       * @memberOf _.support
	       * @type boolean
	       */
	      support.funcNames = typeof Function.name == 'string';

	      /**
	       * Detect if the DOM is supported.
	       *
	       * @memberOf _.support
	       * @type boolean
	       */
	      try {
	        support.dom = document.createDocumentFragment().nodeType === 11;
	      } catch(e) {
	        support.dom = false;
	      }

	      /**
	       * Detect if `arguments` object indexes are non-enumerable.
	       *
	       * In Firefox < 4, IE < 9, PhantomJS, and Safari < 5.1 `arguments` object
	       * indexes are non-enumerable. Chrome < 25 and Node.js < 0.11.0 treat
	       * `arguments` object indexes as non-enumerable and fail `hasOwnProperty`
	       * checks for indexes that exceed their function's formal parameters with
	       * associated values of `0`.
	       *
	       * @memberOf _.support
	       * @type boolean
	       */
	      try {
	        support.nonEnumArgs = !propertyIsEnumerable.call(arguments, 1);
	      } catch(e) {
	        support.nonEnumArgs = true;
	      }
	    }(0, 0));

	    /**
	     * By default, the template delimiters used by lodash are like those in
	     * embedded Ruby (ERB). Change the following template settings to use
	     * alternative delimiters.
	     *
	     * @static
	     * @memberOf _
	     * @type Object
	     */
	    lodash.templateSettings = {

	      /**
	       * Used to detect `data` property values to be HTML-escaped.
	       *
	       * @memberOf _.templateSettings
	       * @type RegExp
	       */
	      'escape': reEscape,

	      /**
	       * Used to detect code to be evaluated.
	       *
	       * @memberOf _.templateSettings
	       * @type RegExp
	       */
	      'evaluate': reEvaluate,

	      /**
	       * Used to detect `data` property values to inject.
	       *
	       * @memberOf _.templateSettings
	       * @type RegExp
	       */
	      'interpolate': reInterpolate,

	      /**
	       * Used to reference the data object in the template text.
	       *
	       * @memberOf _.templateSettings
	       * @type string
	       */
	      'variable': '',

	      /**
	       * Used to import variables into the compiled template.
	       *
	       * @memberOf _.templateSettings
	       * @type Object
	       */
	      'imports': {

	        /**
	         * A reference to the `lodash` function.
	         *
	         * @memberOf _.templateSettings.imports
	         * @type Function
	         */
	        '_': lodash
	      }
	    };

	    /*------------------------------------------------------------------------*/

	    /**
	     * Creates a lazy wrapper object which wraps `value` to enable lazy evaluation.
	     *
	     * @private
	     * @param {*} value The value to wrap.
	     */
	    function LazyWrapper(value) {
	      this.actions = null;
	      this.dir = 1;
	      this.dropCount = 0;
	      this.filtered = false;
	      this.iteratees = null;
	      this.takeCount = POSITIVE_INFINITY;
	      this.views = null;
	      this.wrapped = value;
	    }

	    /**
	     * Creates a clone of the lazy wrapper object.
	     *
	     * @private
	     * @name clone
	     * @memberOf LazyWrapper
	     * @returns {Object} Returns the cloned `LazyWrapper` object.
	     */
	    function lazyClone() {
	      var actions = this.actions,
	          iteratees = this.iteratees,
	          views = this.views,
	          result = new LazyWrapper(this.wrapped);

	      result.actions = actions ? arrayCopy(actions) : null;
	      result.dir = this.dir;
	      result.dropCount = this.dropCount;
	      result.filtered = this.filtered;
	      result.iteratees = iteratees ? arrayCopy(iteratees) : null;
	      result.takeCount = this.takeCount;
	      result.views = views ? arrayCopy(views) : null;
	      return result;
	    }

	    /**
	     * Reverses the direction of lazy iteration.
	     *
	     * @private
	     * @name reverse
	     * @memberOf LazyWrapper
	     * @returns {Object} Returns the new reversed `LazyWrapper` object.
	     */
	    function lazyReverse() {
	      if (this.filtered) {
	        var result = new LazyWrapper(this);
	        result.dir = -1;
	        result.filtered = true;
	      } else {
	        result = this.clone();
	        result.dir *= -1;
	      }
	      return result;
	    }

	    /**
	     * Extracts the unwrapped value from its lazy wrapper.
	     *
	     * @private
	     * @name value
	     * @memberOf LazyWrapper
	     * @returns {*} Returns the unwrapped value.
	     */
	    function lazyValue() {
	      var array = this.wrapped.value();
	      if (!isArray(array)) {
	        return baseWrapperValue(array, this.actions);
	      }
	      var dir = this.dir,
	          isRight = dir < 0,
	          view = getView(0, array.length, this.views),
	          start = view.start,
	          end = view.end,
	          length = end - start,
	          dropCount = this.dropCount,
	          takeCount = nativeMin(length, this.takeCount - dropCount),
	          index = isRight ? end : start - 1,
	          iteratees = this.iteratees,
	          iterLength = iteratees ? iteratees.length : 0,
	          resIndex = 0,
	          result = [];

	      outer:
	      while (length-- && resIndex < takeCount) {
	        index += dir;

	        var iterIndex = -1,
	            value = array[index];

	        while (++iterIndex < iterLength) {
	          var data = iteratees[iterIndex],
	              iteratee = data.iteratee,
	              computed = iteratee(value, index, array),
	              type = data.type;

	          if (type == LAZY_MAP_FLAG) {
	            value = computed;
	          } else if (!computed) {
	            if (type == LAZY_FILTER_FLAG) {
	              continue outer;
	            } else {
	              break outer;
	            }
	          }
	        }
	        if (dropCount) {
	          dropCount--;
	        } else {
	          result[resIndex++] = value;
	        }
	      }
	      return result;
	    }

	    /*------------------------------------------------------------------------*/

	    /**
	     * Creates a cache object to store key/value pairs.
	     *
	     * @private
	     * @static
	     * @name Cache
	     * @memberOf _.memoize
	     */
	    function MapCache() {
	      this.__data__ = {};
	    }

	    /**
	     * Removes `key` and its value from the cache.
	     *
	     * @private
	     * @name delete
	     * @memberOf _.memoize.Cache
	     * @param {string} key The key of the value to remove.
	     * @returns {boolean} Returns `true` if the entry was removed successfully, else `false`.
	     */
	    function mapDelete(key) {
	      return this.has(key) && delete this.__data__[key];
	    }

	    /**
	     * Gets the cached value for `key`.
	     *
	     * @private
	     * @name get
	     * @memberOf _.memoize.Cache
	     * @param {string} key The key of the value to get.
	     * @returns {*} Returns the cached value.
	     */
	    function mapGet(key) {
	      return key == '__proto__' ? undefined : this.__data__[key];
	    }

	    /**
	     * Checks if a cached value for `key` exists.
	     *
	     * @private
	     * @name has
	     * @memberOf _.memoize.Cache
	     * @param {string} key The key of the entry to check.
	     * @returns {boolean} Returns `true` if an entry for `key` exists, else `false`.
	     */
	    function mapHas(key) {
	      return key != '__proto__' && hasOwnProperty.call(this.__data__, key);
	    }

	    /**
	     * Adds `value` to `key` of the cache.
	     *
	     * @private
	     * @name set
	     * @memberOf _.memoize.Cache
	     * @param {string} key The key of the value to cache.
	     * @param {*} value The value to cache.
	     * @returns {Object} Returns the cache object.
	     */
	    function mapSet(key, value) {
	      if (key != '__proto__') {
	        this.__data__[key] = value;
	      }
	      return this;
	    }

	    /*------------------------------------------------------------------------*/

	    /**
	     *
	     * Creates a cache object to store unique values.
	     *
	     * @private
	     * @param {Array} [values] The values to cache.
	     */
	    function SetCache(values) {
	      var length = values ? values.length : 0;

	      this.data = { 'hash': nativeCreate(null), 'set': new Set };
	      while (length--) {
	        this.push(values[length]);
	      }
	    }

	    /**
	     * Checks if `value` is in `cache` mimicking the return signature of
	     * `_.indexOf` by returning `0` if the value is found, else `-1`.
	     *
	     * @private
	     * @param {Object} cache The cache to search.
	     * @param {*} value The value to search for.
	     * @returns {number} Returns `0` if `value` is found, else `-1`.
	     */
	    function cacheIndexOf(cache, value) {
	      var data = cache.data,
	          result = (typeof value == 'string' || isObject(value)) ? data.set.has(value) : data.hash[value];

	      return result ? 0 : -1;
	    }

	    /**
	     * Adds `value` to the cache.
	     *
	     * @private
	     * @name push
	     * @memberOf SetCache
	     * @param {*} value The value to cache.
	     */
	    function cachePush(value) {
	      var data = this.data;
	      if (typeof value == 'string' || isObject(value)) {
	        data.set.add(value);
	      } else {
	        data.hash[value] = true;
	      }
	    }

	    /*------------------------------------------------------------------------*/

	    /**
	     * Copies the values of `source` to `array`.
	     *
	     * @private
	     * @param {Array} source The array to copy values from.
	     * @param {Array} [array=[]] The array to copy values to.
	     * @returns {Array} Returns `array`.
	     */
	    function arrayCopy(source, array) {
	      var index = -1,
	          length = source.length;

	      array || (array = Array(length));
	      while (++index < length) {
	        array[index] = source[index];
	      }
	      return array;
	    }

	    /**
	     * A specialized version of `_.forEach` for arrays without support for callback
	     * shorthands or `this` binding.
	     *
	     * @private
	     * @param {Array} array The array to iterate over.
	     * @param {Function} iteratee The function invoked per iteration.
	     * @returns {Array} Returns `array`.
	     */
	    function arrayEach(array, iteratee) {
	      var index = -1,
	          length = array.length;

	      while (++index < length) {
	        if (iteratee(array[index], index, array) === false) {
	          break;
	        }
	      }
	      return array;
	    }

	    /**
	     * A specialized version of `_.forEachRight` for arrays without support for
	     * callback shorthands or `this` binding.
	     *
	     * @private
	     * @param {Array} array The array to iterate over.
	     * @param {Function} iteratee The function invoked per iteration.
	     * @returns {Array} Returns `array`.
	     */
	    function arrayEachRight(array, iteratee) {
	      var length = array.length;

	      while (length--) {
	        if (iteratee(array[length], length, array) === false) {
	          break;
	        }
	      }
	      return array;
	    }

	    /**
	     * A specialized version of `_.every` for arrays without support for callback
	     * shorthands or `this` binding.
	     *
	     * @private
	     * @param {Array} array The array to iterate over.
	     * @param {Function} predicate The function invoked per iteration.
	     * @returns {boolean} Returns `true` if all elements pass the predicate check,
	     *  else `false`.
	     */
	    function arrayEvery(array, predicate) {
	      var index = -1,
	          length = array.length;

	      while (++index < length) {
	        if (!predicate(array[index], index, array)) {
	          return false;
	        }
	      }
	      return true;
	    }

	    /**
	     * A specialized version of `_.filter` for arrays without support for callback
	     * shorthands or `this` binding.
	     *
	     * @private
	     * @param {Array} array The array to iterate over.
	     * @param {Function} predicate The function invoked per iteration.
	     * @returns {Array} Returns the new filtered array.
	     */
	    function arrayFilter(array, predicate) {
	      var index = -1,
	          length = array.length,
	          resIndex = -1,
	          result = [];

	      while (++index < length) {
	        var value = array[index];
	        if (predicate(value, index, array)) {
	          result[++resIndex] = value;
	        }
	      }
	      return result;
	    }

	    /**
	     * A specialized version of `_.map` for arrays without support for callback
	     * shorthands or `this` binding.
	     *
	     * @private
	     * @param {Array} array The array to iterate over.
	     * @param {Function} iteratee The function invoked per iteration.
	     * @returns {Array} Returns the new mapped array.
	     */
	    function arrayMap(array, iteratee) {
	      var index = -1,
	          length = array.length,
	          result = Array(length);

	      while (++index < length) {
	        result[index] = iteratee(array[index], index, array);
	      }
	      return result;
	    }

	    /**
	     * A specialized version of `_.max` for arrays without support for iteratees.
	     *
	     * @private
	     * @param {Array} array The array to iterate over.
	     * @returns {*} Returns the maximum value.
	     */
	    function arrayMax(array) {
	      var index = -1,
	          length = array.length,
	          result = NEGATIVE_INFINITY;

	      while (++index < length) {
	        var value = array[index];
	        if (value > result) {
	          result = value;
	        }
	      }
	      return result;
	    }

	    /**
	     * A specialized version of `_.min` for arrays without support for iteratees.
	     *
	     * @private
	     * @param {Array} array The array to iterate over.
	     * @returns {*} Returns the minimum value.
	     */
	    function arrayMin(array) {
	      var index = -1,
	          length = array.length,
	          result = POSITIVE_INFINITY;

	      while (++index < length) {
	        var value = array[index];
	        if (value < result) {
	          result = value;
	        }
	      }
	      return result;
	    }

	    /**
	     * A specialized version of `_.reduce` for arrays without support for callback
	     * shorthands or `this` binding.
	     *
	     * @private
	     * @param {Array} array The array to iterate over.
	     * @param {Function} iteratee The function invoked per iteration.
	     * @param {*} [accumulator] The initial value.
	     * @param {boolean} [initFromArray] Specify using the first element of `array`
	     *  as the initial value.
	     * @returns {*} Returns the accumulated value.
	     */
	    function arrayReduce(array, iteratee, accumulator, initFromArray) {
	      var index = -1,
	          length = array.length;

	      if (initFromArray && length) {
	        accumulator = array[++index];
	      }
	      while (++index < length) {
	        accumulator = iteratee(accumulator, array[index], index, array);
	      }
	      return accumulator;
	    }

	    /**
	     * A specialized version of `_.reduceRight` for arrays without support for
	     * callback shorthands or `this` binding.
	     *
	     * @private
	     * @param {Array} array The array to iterate over.
	     * @param {Function} iteratee The function invoked per iteration.
	     * @param {*} [accumulator] The initial value.
	     * @param {boolean} [initFromArray] Specify using the last element of `array`
	     *  as the initial value.
	     * @returns {*} Returns the accumulated value.
	     */
	    function arrayReduceRight(array, iteratee, accumulator, initFromArray) {
	      var length = array.length;
	      if (initFromArray && length) {
	        accumulator = array[--length];
	      }
	      while (length--) {
	        accumulator = iteratee(accumulator, array[length], length, array);
	      }
	      return accumulator;
	    }

	    /**
	     * A specialized version of `_.some` for arrays without support for callback
	     * shorthands or `this` binding.
	     *
	     * @private
	     * @param {Array} array The array to iterate over.
	     * @param {Function} predicate The function invoked per iteration.
	     * @returns {boolean} Returns `true` if any element passes the predicate check,
	     *  else `false`.
	     */
	    function arraySome(array, predicate) {
	      var index = -1,
	          length = array.length;

	      while (++index < length) {
	        if (predicate(array[index], index, array)) {
	          return true;
	        }
	      }
	      return false;
	    }

	    /**
	     * Used by `_.defaults` to customize its `_.assign` use.
	     *
	     * @private
	     * @param {*} objectValue The destination object property value.
	     * @param {*} sourceValue The source object property value.
	     * @returns {*} Returns the value to assign to the destination object.
	     */
	    function assignDefaults(objectValue, sourceValue) {
	      return typeof objectValue == 'undefined' ? sourceValue : objectValue;
	    }

	    /**
	     * Used by `_.template` to customize its `_.assign` use.
	     *
	     * **Note:** This method is like `assignDefaults` except that it ignores
	     * inherited property values when checking if a property is `undefined`.
	     *
	     * @private
	     * @param {*} objectValue The destination object property value.
	     * @param {*} sourceValue The source object property value.
	     * @param {string} key The key associated with the object and source values.
	     * @param {Object} object The destination object.
	     * @returns {*} Returns the value to assign to the destination object.
	     */
	    function assignOwnDefaults(objectValue, sourceValue, key, object) {
	      return (typeof objectValue == 'undefined' || !hasOwnProperty.call(object, key))
	        ? sourceValue
	        : objectValue;
	    }

	    /**
	     * The base implementation of `_.assign` without support for argument juggling,
	     * multiple sources, and `this` binding `customizer` functions.
	     *
	     * @private
	     * @param {Object} object The destination object.
	     * @param {Object} source The source object.
	     * @param {Function} [customizer] The function to customize assigning values.
	     * @returns {Object} Returns the destination object.
	     */
	    function baseAssign(object, source, customizer) {
	      var props = keys(source);
	      if (!customizer) {
	        return baseCopy(source, object, props);
	      }
	      var index = -1,
	          length = props.length

	      while (++index < length) {
	        var key = props[index],
	            value = object[key],
	            result = customizer(value, source[key], key, object, source);

	        if ((result === result ? result !== value : value === value) ||
	            (typeof value == 'undefined' && !(key in object))) {
	          object[key] = result;
	        }
	      }
	      return object;
	    }

	    /**
	     * The base implementation of `_.at` without support for strings and individual
	     * key arguments.
	     *
	     * @private
	     * @param {Array|Object} collection The collection to iterate over.
	     * @param {number[]|string[]} [props] The property names or indexes of elements to pick.
	     * @returns {Array} Returns the new array of picked elements.
	     */
	    function baseAt(collection, props) {
	      var index = -1,
	          length = collection.length,
	          isArr = isLength(length),
	          propsLength = props.length,
	          result = Array(propsLength);

	      while(++index < propsLength) {
	        var key = props[index];
	        if (isArr) {
	          key = parseFloat(key);
	          result[index] = isIndex(key, length) ? collection[key] : undefined;
	        } else {
	          result[index] = collection[key];
	        }
	      }
	      return result;
	    }

	    /**
	     * Copies the properties of `source` to `object`.
	     *
	     * @private
	     * @param {Object} source The object to copy properties from.
	     * @param {Object} [object={}] The object to copy properties to.
	     * @param {Array} props The property names to copy.
	     * @returns {Object} Returns `object`.
	     */
	    function baseCopy(source, object, props) {
	      if (!props) {
	        props = object;
	        object = {};
	      }
	      var index = -1,
	          length = props.length;

	      while (++index < length) {
	        var key = props[index];
	        object[key] = source[key];
	      }
	      return object;
	    }

	    /**
	     * The base implementation of `_.bindAll` without support for individual
	     * method name arguments.
	     *
	     * @private
	     * @param {Object} object The object to bind and assign the bound methods to.
	     * @param {string[]} methodNames The object method names to bind.
	     * @returns {Object} Returns `object`.
	     */
	    function baseBindAll(object, methodNames) {
	      var index = -1,
	          length = methodNames.length;

	      while (++index < length) {
	        var key = methodNames[index];
	        object[key] = createWrapper(object[key], BIND_FLAG, object);
	      }
	      return object;
	    }

	    /**
	     * The base implementation of `_.callback` which supports specifying the
	     * number of arguments to provide to `func`.
	     *
	     * @private
	     * @param {*} [func=_.identity] The value to convert to a callback.
	     * @param {*} [thisArg] The `this` binding of `func`.
	     * @param {number} [argCount] The number of arguments to provide to `func`.
	     * @returns {Function} Returns the callback.
	     */
	    function baseCallback(func, thisArg, argCount) {
	      var type = typeof func;
	      if (type == 'function') {
	        return (typeof thisArg != 'undefined' && isBindable(func))
	          ? bindCallback(func, thisArg, argCount)
	          : func;
	      }
	      if (func == null) {
	        return identity;
	      }
	      // Handle "_.property" and "_.matches" style callback shorthands.
	      return type == 'object'
	        ? baseMatches(func)
	        : baseProperty(func + '');
	    }

	    /**
	     * The base implementation of `_.clone` without support for argument juggling
	     * and `this` binding `customizer` functions.
	     *
	     * @private
	     * @param {*} value The value to clone.
	     * @param {boolean} [isDeep] Specify a deep clone.
	     * @param {Function} [customizer] The function to customize cloning values.
	     * @param {string} [key] The key of `value`.
	     * @param {Object} [object] The object `value` belongs to.
	     * @param {Array} [stackA=[]] Tracks traversed source objects.
	     * @param {Array} [stackB=[]] Associates clones with source counterparts.
	     * @returns {*} Returns the cloned value.
	     */
	    function baseClone(value, isDeep, customizer, key, object, stackA, stackB) {
	      var result;
	      if (customizer) {
	        result = object ? customizer(value, key, object) : customizer(value);
	      }
	      if (typeof result != 'undefined') {
	        return result;
	      }
	      if (!isObject(value)) {
	        return value;
	      }
	      var isArr = isArray(value);
	      if (isArr) {
	        result = initCloneArray(value);
	        if (!isDeep) {
	          return arrayCopy(value, result);
	        }
	      } else {
	        var tag = objToString.call(value),
	            isFunc = tag == funcTag;

	        if (tag == objectTag || tag == argsTag || (isFunc && !object)) {
	          result = initCloneObject(isFunc ? {} : value);
	          if (!isDeep) {
	            return baseCopy(value, result, keys(value));
	          }
	        } else {
	          return cloneableTags[tag]
	            ? initCloneByTag(value, tag, isDeep)
	            : (object ? value : {});
	        }
	      }
	      // Check for circular references and return corresponding clone.
	      stackA || (stackA = []);
	      stackB || (stackB = []);

	      var length = stackA.length;
	      while (length--) {
	        if (stackA[length] == value) {
	          return stackB[length];
	        }
	      }
	      // Add the source value to the stack of traversed objects and associate it with its clone.
	      stackA.push(value);
	      stackB.push(result);

	      // Recursively populate clone (susceptible to call stack limits).
	      (isArr ? arrayEach : baseForOwn)(value, function(subValue, key) {
	        result[key] = baseClone(subValue, isDeep, customizer, key, value, stackA, stackB);
	      });
	      return result;
	    }

	    /**
	     * The base implementation of `_.create` without support for assigning
	     * properties to the created object.
	     *
	     * @private
	     * @param {Object} prototype The object to inherit from.
	     * @returns {Object} Returns the new object.
	     */
	    var baseCreate = (function() {
	      function Object() {}
	      return function(prototype) {
	        if (isObject(prototype)) {
	          Object.prototype = prototype;
	          var result = new Object;
	          Object.prototype = null;
	        }
	        return result || context.Object();
	      };
	    }());

	    /**
	     * The base implementation of `_.delay` and `_.defer` which accepts an index
	     * of where to slice the arguments to provide to `func`.
	     *
	     * @private
	     * @param {Function} func The function to delay.
	     * @param {number} wait The number of milliseconds to delay invocation.
	     * @param {Object} args The `arguments` object to slice and provide to `func`.
	     * @returns {number} Returns the timer id.
	     */
	    function baseDelay(func, wait, args, fromIndex) {
	      if (!isFunction(func)) {
	        throw new TypeError(FUNC_ERROR_TEXT);
	      }
	      return setTimeout(function() { func.apply(undefined, baseSlice(args, fromIndex)); }, wait);
	    }

	    /**
	     * The base implementation of `_.difference` which accepts a single array
	     * of values to exclude.
	     *
	     * @private
	     * @param {Array} array The array to inspect.
	     * @param {Array} values The values to exclude.
	     * @returns {Array} Returns the new array of filtered values.
	     */
	    function baseDifference(array, values) {
	      var length = array ? array.length : 0,
	          result = [];

	      if (!length) {
	        return result;
	      }
	      var index = -1,
	          indexOf = getIndexOf(),
	          isCommon = indexOf == baseIndexOf,
	          cache = isCommon && values.length >= 200 && createCache(values),
	          valuesLength = values.length;

	      if (cache) {
	        indexOf = cacheIndexOf;
	        isCommon = false;
	        values = cache;
	      }
	      outer:
	      while (++index < length) {
	        var value = array[index];

	        if (isCommon && value === value) {
	          var valuesIndex = valuesLength;
	          while (valuesIndex--) {
	            if (values[valuesIndex] === value) {
	              continue outer;
	            }
	          }
	          result.push(value);
	        }
	        else if (indexOf(values, value) < 0) {
	          result.push(value);
	        }
	      }
	      return result;
	    }

	    /**
	     * The base implementation of `_.forEach` without support for callback
	     * shorthands and `this` binding.
	     *
	     * @private
	     * @param {Array|Object|string} collection The collection to iterate over.
	     * @param {Function} iteratee The function invoked per iteration.
	     * @returns {Array|Object|string} Returns `collection`.
	     */
	    function baseEach(collection, iteratee) {
	      var length = collection ? collection.length : 0;
	      if (!isLength(length)) {
	        return baseForOwn(collection, iteratee);
	      }
	      var index = -1,
	          iterable = toObject(collection);

	      while (++index < length) {
	        if (iteratee(iterable[index], index, iterable) === false) {
	          break;
	        }
	      }
	      return collection;
	    }

	    /**
	     * The base implementation of `_.forEachRight` without support for callback
	     * shorthands and `this` binding.
	     *
	     * @private
	     * @param {Array|Object|string} collection The collection to iterate over.
	     * @param {Function} iteratee The function invoked per iteration.
	     * @returns {Array|Object|string} Returns `collection`.
	     */
	    function baseEachRight(collection, iteratee) {
	      var length = collection ? collection.length : 0;
	      if (!isLength(length)) {
	        return baseForOwnRight(collection, iteratee);
	      }
	      var iterable = toObject(collection);
	      while (length--) {
	        if (iteratee(iterable[length], length, iterable) === false) {
	          break;
	        }
	      }
	      return collection;
	    }

	    /**
	     * The base implementation of `_.every` without support for callback
	     * shorthands or `this` binding.
	     *
	     * @private
	     * @param {Array|Object|string} collection The collection to iterate over.
	     * @param {Function} predicate The function invoked per iteration.
	     * @returns {boolean} Returns `true` if all elements pass the predicate check,
	     *  else `false`
	     */
	    function baseEvery(collection, predicate) {
	      var result = true;
	      baseEach(collection, function(value, index, collection) {
	        result = !!predicate(value, index, collection);
	        return result;
	      });
	      return result;
	    }

	    /**
	     * The base implementation of `_.filter` without support for callback
	     * shorthands or `this` binding.
	     *
	     * @private
	     * @param {Array|Object|string} collection The collection to iterate over.
	     * @param {Function} predicate The function invoked per iteration.
	     * @returns {Array} Returns the new filtered array.
	     */
	    function baseFilter(collection, predicate) {
	      var result = [];
	      baseEach(collection, function(value, index, collection) {
	        if (predicate(value, index, collection)) {
	          result.push(value);
	        }
	      });
	      return result;
	    }

	    /**
	     * The base implementation of `_.find`, `_.findLast`, `_.findKey`, and `_.findLastKey`,
	     * without support for callback shorthands and `this` binding, which iterates
	     * over `collection` using the provided `eachFunc`.
	     *
	     * @private
	     * @param {Array|Object|string} collection The collection to search.
	     * @param {Function} predicate The function invoked per iteration.
	     * @param {Function} eachFunc The function to iterate over `collection`.
	     * @param {boolean} [retKey] Specify returning the key of the found element
	     *  instead of the element itself.
	     * @returns {*} Returns the found element or its key, else `undefined`.
	     */
	    function baseFind(collection, predicate, eachFunc, retKey) {
	      var result;
	      eachFunc(collection, function(value, key, collection) {
	        if (predicate(value, key, collection)) {
	          result = retKey ? key : value;
	          return false;
	        }
	      });
	      return result;
	    }

	    /**
	     * The base implementation of `_.flatten` with added support for restricting
	     * flattening and specifying the start index.
	     *
	     * @private
	     * @param {Array} array The array to flatten.
	     * @param {boolean} [isDeep] Specify a deep flatten.
	     * @param {boolean} [isStrict] Restrict flattening to arrays and `arguments` objects.
	     * @param {number} [fromIndex=0] The index to start from.
	     * @returns {Array} Returns the new flattened array.
	     */
	    function baseFlatten(array, isDeep, isStrict, fromIndex) {
	      var index = (fromIndex || 0) - 1,
	          length = array.length,
	          resIndex = -1,
	          result = [];

	      while (++index < length) {
	        var value = array[index];

	        if (isObjectLike(value) && isLength(value.length) && (isArray(value) || isArguments(value))) {
	          if (isDeep) {
	            // Recursively flatten arrays (susceptible to call stack limits).
	            value = baseFlatten(value, isDeep, isStrict);
	          }
	          var valIndex = -1,
	              valLength = value.length;

	          result.length += valLength;
	          while (++valIndex < valLength) {
	            result[++resIndex] = value[valIndex];
	          }
	        } else if (!isStrict) {
	          result[++resIndex] = value;
	        }
	      }
	      return result;
	    }

	    /**
	     * The base implementation of `baseForIn` and `baseForOwn` which iterates
	     * over `object` properties returned by `keysFunc` invoking `iteratee` for
	     * each property. Iterator functions may exit iteration early by explicitly
	     * returning `false`.
	     *
	     * @private
	     * @param {Object} object The object to iterate over.
	     * @param {Function} iteratee The function invoked per iteration.
	     * @param {Function} keysFunc The function to get the keys of `object`.
	     * @returns {Object} Returns `object`.
	     */
	    function baseFor(object, iteratee, keysFunc) {
	      var index = -1,
	          iterable = toObject(object),
	          props = keysFunc(object),
	          length = props.length;

	      while (++index < length) {
	        var key = props[index];
	        if (iteratee(iterable[key], key, iterable) === false) {
	          break;
	        }
	      }
	      return object;
	    }

	    /**
	     * This function is like `baseFor` except that it iterates over properties
	     * in the opposite order.
	     *
	     * @private
	     * @param {Object} object The object to iterate over.
	     * @param {Function} iteratee The function invoked per iteration.
	     * @param {Function} keysFunc The function to get the keys of `object`.
	     * @returns {Object} Returns `object`.
	     */
	    function baseForRight(object, iteratee, keysFunc) {
	      var iterable = toObject(object),
	          props = keysFunc(object),
	          length = props.length;

	      while (length--) {
	        var key = props[length];
	        if (iteratee(iterable[key], key, iterable) === false) {
	          break;
	        }
	      }
	      return object;
	    }

	    /**
	     * The base implementation of `_.forIn` without support for callback
	     * shorthands and `this` binding.
	     *
	     * @private
	     * @param {Object} object The object to iterate over.
	     * @param {Function} iteratee The function invoked per iteration.
	     * @returns {Object} Returns `object`.
	     */
	    function baseForIn(object, iteratee) {
	      return baseFor(object, iteratee, keysIn);
	    }

	    /**
	     * The base implementation of `_.forOwn` without support for callback
	     * shorthands and `this` binding.
	     *
	     * @private
	     * @param {Object} object The object to iterate over.
	     * @param {Function} iteratee The function invoked per iteration.
	     * @returns {Object} Returns `object`.
	     */
	    function baseForOwn(object, iteratee) {
	      return baseFor(object, iteratee, keys);
	    }

	    /**
	     * The base implementation of `_.forOwnRight` without support for callback
	     * shorthands and `this` binding.
	     *
	     * @private
	     * @param {Object} object The object to iterate over.
	     * @param {Function} iteratee The function invoked per iteration.
	     * @returns {Object} Returns `object`.
	     */
	    function baseForOwnRight(object, iteratee) {
	      return baseForRight(object, iteratee, keys);
	    }

	    /**
	     * The base implementation of `_.functions` which creates an array of
	     * `object` function property names filtered from those provided.
	     *
	     * @private
	     * @param {Object} object The object to inspect.
	     * @param {Array} props The property names to filter.
	     * @returns {Array} Returns the new array of filtered property names.
	     */
	    function baseFunctions(object, props) {
	      var index = -1,
	          length = props.length,
	          resIndex = -1,
	          result = [];

	      while (++index < length) {
	        var key = props[index];
	        if (isFunction(object[key])) {
	          result[++resIndex] = key;
	        }
	      }
	      return result;
	    }

	    /**
	     * The base implementation of `_.invoke` which requires additional arguments
	     * to be provided as an array of arguments rather than individually.
	     *
	     * @private
	     * @param {Array|Object|string} collection The collection to iterate over.
	     * @param {Function|string} methodName The name of the method to invoke or
	     *  the function invoked per iteration.
	     * @param {Array} [args] The arguments to invoke the method with.
	     * @returns {Array} Returns the array of results.
	     */
	    function baseInvoke(collection, methodName, args) {
	      var index = -1,
	          isFunc = typeof methodName == 'function',
	          length = collection ? collection.length : 0,
	          result = isLength(length) ? Array(length) : [];

	      baseEach(collection, function(value) {
	        var func = isFunc ? methodName : (value != null && value[methodName]);
	        result[++index] = func ? func.apply(value, args) : undefined;
	      });
	      return result;
	    }

	    /**
	     * The base implementation of `_.isEqual` without support for `this` binding
	     * `customizer` functions.
	     *
	     * @private
	     * @param {*} value The value to compare.
	     * @param {*} other The other value to compare.
	     * @param {Function} [customizer] The function to customize comparing values.
	     * @param {boolean} [isWhere] Specify performing partial comparisons.
	     * @param {Array} [stackA] Tracks traversed `value` objects.
	     * @param {Array} [stackB] Tracks traversed `other` objects.
	     * @returns {boolean} Returns `true` if the values are equivalent, else `false`.
	     */
	    function baseIsEqual(value, other, customizer, isWhere, stackA, stackB) {
	      // Exit early for identical values.
	      if (value === other) {
	        // Treat `+0` vs. `-0` as not equal.
	        return value !== 0 || (1 / value == 1 / other);
	      }
	      var valType = typeof value,
	          othType = typeof other;

	      // Exit early for unlike primitive values.
	      if ((valType != 'function' && valType != 'object' && othType != 'function' && othType != 'object') ||
	          value == null || other == null) {
	        // Return `false` unless both values are `NaN`.
	        return value !== value && other !== other;
	      }
	      return baseIsEqualDeep(value, other, baseIsEqual, customizer, isWhere, stackA, stackB);
	    }

	    /**
	     * A specialized version of `baseIsEqual` for arrays and objects which performs
	     * deep comparisons and tracks traversed objects enabling objects with circular
	     * references to be compared.
	     *
	     * @private
	     * @param {Object} object The object to compare.
	     * @param {Object} other The other object to compare.
	     * @param {Function} equalFunc The function to determine equivalents of values.
	     * @param {Function} [customizer] The function to customize comparing objects.
	     * @param {boolean} [isWhere] Specify performing partial comparisons.
	     * @param {Array} [stackA=[]] Tracks traversed `value` objects.
	     * @param {Array} [stackB=[]] Tracks traversed `other` objects.
	     * @returns {boolean} Returns `true` if the objects are equivalent, else `false`.
	     */
	    function baseIsEqualDeep(object, other, equalFunc, customizer, isWhere, stackA, stackB) {
	      var objIsArr = isArray(object),
	          othIsArr = isArray(other),
	          objTag = arrayTag,
	          othTag = arrayTag;

	      if (!objIsArr) {
	        objTag = objToString.call(object);
	        if (objTag == argsTag) {
	          objTag = objectTag;
	        } else if (objTag != objectTag) {
	          objIsArr = isTypedArray(object);
	        }
	      }
	      if (!othIsArr) {
	        othTag = objToString.call(other);
	        if (othTag == argsTag) {
	          othTag = objectTag;
	        } else if (othTag != objectTag) {
	          othIsArr = isTypedArray(other);
	        }
	      }
	      var objIsObj = objTag == objectTag,
	          othIsObj = othTag == objectTag,
	          isSameTag = objTag == othTag;

	      if (isSameTag && !(objIsArr || objIsObj)) {
	        return equalByTag(object, other, objTag);
	      }
	      var valWrapped = objIsObj && hasOwnProperty.call(object, '__wrapped__'),
	          othWrapped = othIsObj && hasOwnProperty.call(other, '__wrapped__');

	      if (valWrapped || othWrapped) {
	        return equalFunc(valWrapped ? object.value() : object, othWrapped ? other.value() : other, customizer, isWhere, stackA, stackB);
	      }
	      if (!isSameTag) {
	        return false;
	      }
	      // Assume cyclic values are equal.
	      // For more information on detecting circular references see https://es5.github.io/#JO.
	      stackA || (stackA = []);
	      stackB || (stackB = []);

	      var length = stackA.length;
	      while (length--) {
	        if (stackA[length] == object) {
	          return stackB[length] == other;
	        }
	      }
	      // Add `object` and `other` to the stack of traversed objects.
	      stackA.push(object);
	      stackB.push(other);

	      var result = (objIsArr ? equalArrays : equalObjects)(object, other, equalFunc, customizer, isWhere, stackA, stackB);

	      stackA.pop();
	      stackB.pop();

	      return result;
	    }

	    /**
	     * The base implementation of `_.isMatch` without support for callback
	     * shorthands or `this` binding.
	     *
	     * @private
	     * @param {Object} source The object to inspect.
	     * @param {Array} props The source property names to match.
	     * @param {Array} values The source values to match.
	     * @param {Array} strictCompareFlags Strict comparison flags for source values.
	     * @param {Function} [customizer] The function to customize comparing objects.
	     * @returns {boolean} Returns `true` if `object` is a match, else `false`.
	     */
	    function baseIsMatch(object, props, values, strictCompareFlags, customizer) {
	      var length = props.length;
	      if (object == null) {
	        return !length;
	      }
	      var index = -1,
	          noCustomizer = !customizer;

	      while (++index < length) {
	        if ((noCustomizer && strictCompareFlags[index])
	              ? values[index] !== object[props[index]]
	              : !hasOwnProperty.call(object, props[index])
	            ) {
	          return false;
	        }
	      }
	      index = -1;
	      while (++index < length) {
	        var key = props[index];
	        if (noCustomizer && strictCompareFlags[index]) {
	          var result = hasOwnProperty.call(object, key);
	        } else {
	          var objValue = object[key],
	              srcValue = values[index];

	          result = customizer ? customizer(objValue, srcValue, key) : undefined;
	          if (typeof result == 'undefined') {
	            result = baseIsEqual(srcValue, objValue, customizer, true);
	          }
	        }
	        if (!result) {
	          return false;
	        }
	      }
	      return true;
	    }

	    /**
	     * The base implementation of `_.map` without support for callback shorthands
	     * or `this` binding.
	     *
	     * @private
	     * @param {Array|Object|string} collection The collection to iterate over.
	     * @param {Function} iteratee The function invoked per iteration.
	     * @returns {Array} Returns the new mapped array.
	     */
	    function baseMap(collection, iteratee) {
	      var result = [];
	      baseEach(collection, function(value, key, collection) {
	        result.push(iteratee(value, key, collection));
	      });
	      return result;
	    }

	    /**
	     * The base implementation of `_.matches` which supports specifying whether
	     * `source` should be cloned.
	     *
	     * @private
	     * @param {Object} source The object of property values to match.
	     * @returns {Function} Returns the new function.
	     */
	    function baseMatches(source) {
	      var props = keys(source),
	          length = props.length;

	      if (length == 1) {
	        var key = props[0],
	            value = source[key];

	        if (isStrictComparable(value)) {
	          return function(object) {
	            return object != null && value === object[key] && hasOwnProperty.call(object, key);
	          };
	        }
	      }
	      var values = Array(length),
	          strictCompareFlags = Array(length);

	      while (length--) {
	        value = source[props[length]];
	        values[length] = value;
	        strictCompareFlags[length] = isStrictComparable(value);
	      }
	      return function(object) {
	        return baseIsMatch(object, props, values, strictCompareFlags);
	      };
	    }

	    /**
	     * The base implementation of `_.merge` without support for argument juggling,
	     * multiple sources, and `this` binding `customizer` functions.
	     *
	     * @private
	     * @param {Object} object The destination object.
	     * @param {Object} source The source object.
	     * @param {Function} [customizer] The function to customize merging properties.
	     * @param {Array} [stackA=[]] Tracks traversed source objects.
	     * @param {Array} [stackB=[]] Associates values with source counterparts.
	     * @returns {Object} Returns the destination object.
	     */
	    function baseMerge(object, source, customizer, stackA, stackB) {
	      var isSrcArr = isLength(source.length) && (isArray(source) || isTypedArray(source));

	      (isSrcArr ? arrayEach : baseForOwn)(source, function(srcValue, key, source) {
	        if (isObjectLike(srcValue)) {
	          stackA || (stackA = []);
	          stackB || (stackB = []);
	          return baseMergeDeep(object, source, key, baseMerge, customizer, stackA, stackB);
	        }
	        var value = object[key],
	            result = customizer ? customizer(value, srcValue, key, object, source) : undefined,
	            isCommon = typeof result == 'undefined';

	        if (isCommon) {
	          result = srcValue;
	        }
	        if ((isSrcArr || typeof result != 'undefined') &&
	            (isCommon || (result === result ? result !== value : value === value))) {
	          object[key] = result;
	        }
	      });
	      return object;
	    }

	    /**
	     * A specialized version of `baseMerge` for arrays and objects which performs
	     * deep merges and tracks traversed objects enabling objects with circular
	     * references to be merged.
	     *
	     * @private
	     * @param {Object} object The destination object.
	     * @param {Object} source The source object.
	     * @param {string} key The key of the value to merge.
	     * @param {Function} mergeFunc The function to merge values.
	     * @param {Function} [customizer] The function to customize merging properties.
	     * @param {Array} [stackA=[]] Tracks traversed source objects.
	     * @param {Array} [stackB=[]] Associates values with source counterparts.
	     * @returns {boolean} Returns `true` if the objects are equivalent, else `false`.
	     */
	    function baseMergeDeep(object, source, key, mergeFunc, customizer, stackA, stackB) {
	      var length = stackA.length,
	          srcValue = source[key];

	      while (length--) {
	        if (stackA[length] == srcValue) {
	          object[key] = stackB[length];
	          return;
	        }
	      }
	      var value = object[key],
	          result = customizer ? customizer(value, srcValue, key, object, source) : undefined,
	          isCommon = typeof result == 'undefined';

	      if (isCommon) {
	        result = srcValue;
	        if (isLength(srcValue.length) && (isArray(srcValue) || isTypedArray(srcValue))) {
	          result = isArray(value)
	            ? value
	            : (value ? arrayCopy(value) : []);
	        }
	        else if (isPlainObject(srcValue) || isArguments(srcValue)) {
	          result = isArguments(value)
	            ? toPlainObject(value)
	            : (isPlainObject(value) ? value : {});
	        }
	        else {
	          isCommon = false;
	        }
	      }
	      // Add the source value to the stack of traversed objects and associate
	      // it with its merged value.
	      stackA.push(srcValue);
	      stackB.push(result);

	      if (isCommon) {
	        // Recursively merge objects and arrays (susceptible to call stack limits).
	        object[key] = mergeFunc(result, srcValue, customizer, stackA, stackB);
	      } else if (result === result ? result !== value : value === value) {
	        object[key] = result;
	      }
	    }

	    /**
	     * The base implementation of `_.property` which does not coerce `key` to a string.
	     *
	     * @private
	     * @param {string} key The key of the property to get.
	     * @returns {Function} Returns the new function.
	     */
	    function baseProperty(key) {
	      return function(object) {
	        return object == null ? undefined : object[key];
	      };
	    }

	    /**
	     * The base implementation of `_.pullAt` without support for individual
	     * index arguments.
	     *
	     * @private
	     * @param {Array} array The array to modify.
	     * @param {number[]} indexes The indexes of elements to remove.
	     * @returns {Array} Returns the new array of removed elements.
	     */
	    function basePullAt(array, indexes) {
	      var length = indexes.length,
	          result = baseAt(array, indexes);

	      indexes.sort(baseCompareAscending);
	      while (length--) {
	        var index = parseFloat(indexes[length]);
	        if (index != previous && isIndex(index)) {
	          var previous = index;
	          splice.call(array, index, 1);
	        }
	      }
	      return result;
	    }

	    /**
	     * The base implementation of `_.random` without support for argument juggling
	     * and returning floating-point numbers.
	     *
	     * @private
	     * @param {number} min The minimum possible value.
	     * @param {number} max The maximum possible value.
	     * @returns {number} Returns the random number.
	     */
	    function baseRandom(min, max) {
	      return min + floor(nativeRandom() * (max - min + 1));
	    }

	    /**
	     * The base implementation of `_.reduce` and `_.reduceRight` without support
	     * for callback shorthands or `this` binding, which iterates over `collection`
	     * using the provided `eachFunc`.
	     *
	     * @private
	     * @param {Array|Object|string} collection The collection to iterate over.
	     * @param {Function} iteratee The function invoked per iteration.
	     * @param {*} accumulator The initial value.
	     * @param {boolean} initFromCollection Specify using the first or last element
	     *  of `collection` as the initial value.
	     * @param {Function} eachFunc The function to iterate over `collection`.
	     * @returns {*} Returns the accumulated value.
	     */
	    function baseReduce(collection, iteratee, accumulator, initFromCollection, eachFunc) {
	      eachFunc(collection, function(value, index, collection) {
	        accumulator = initFromCollection
	          ? (initFromCollection = false, value)
	          : iteratee(accumulator, value, index, collection)
	      });
	      return accumulator;
	    }

	    /**
	     * The base implementation of `setData` without support for hot loop detection.
	     *
	     * @private
	     * @param {Function} func The function to associate metadata with.
	     * @param {*} data The metadata.
	     * @returns {Function} Returns `func`.
	     */
	    var baseSetData = !metaMap ? identity : function(func, data) {
	      metaMap.set(func, data);
	      return func;
	    };

	    /**
	     * The base implementation of `_.slice` without an iteratee call guard.
	     *
	     * @private
	     * @param {Array} array The array to slice.
	     * @param {number} [start=0] The start position.
	     * @param {number} [end=array.length] The end position.
	     * @returns {Array} Returns the slice of `array`.
	     */
	    function baseSlice(array, start, end) {
	      var index = -1,
	          length = array.length;

	      start = start == null ? 0 : (+start || 0);
	      if (start < 0) {
	        start = -start > length ? 0 : (length + start);
	      }
	      end = (typeof end == 'undefined' || end > length) ? length : (+end || 0);
	      if (end < 0) {
	        end += length;
	      }
	      length = start > end ? 0 : (end - start) >>> 0;
	      start >>>= 0;

	      var result = Array(length);
	      while (++index < length) {
	        result[index] = array[index + start];
	      }
	      return result;
	    }

	    /**
	     * The base implementation of `_.some` without support for callback shorthands
	     * or `this` binding.
	     *
	     * @private
	     * @param {Array|Object|string} collection The collection to iterate over.
	     * @param {Function} predicate The function invoked per iteration.
	     * @returns {boolean} Returns `true` if any element passes the predicate check,
	     *  else `false`.
	     */
	    function baseSome(collection, predicate) {
	      var result;

	      baseEach(collection, function(value, index, collection) {
	        result = predicate(value, index, collection);
	        return !result;
	      });
	      return !!result;
	    }

	    /**
	     * The base implementation of `_.uniq` without support for callback shorthands
	     * and `this` binding.
	     *
	     * @private
	     * @param {Array} array The array to inspect.
	     * @param {Function} [iteratee] The function invoked per iteration.
	     * @returns {Array} Returns the new duplicate-value-free array.
	     */
	    function baseUniq(array, iteratee) {
	      var index = -1,
	          indexOf = getIndexOf(),
	          length = array.length,
	          isCommon = indexOf == baseIndexOf,
	          isLarge = isCommon && length >= 200,
	          seen = isLarge && createCache(),
	          result = [];

	      if (seen) {
	        indexOf = cacheIndexOf;
	        isCommon = false;
	      } else {
	        isLarge = false;
	        seen = iteratee ? [] : result;
	      }
	      outer:
	      while (++index < length) {
	        var value = array[index],
	            computed = iteratee ? iteratee(value, index, array) : value;

	        if (isCommon && value === value) {
	          var seenIndex = seen.length;
	          while (seenIndex--) {
	            if (seen[seenIndex] === computed) {
	              continue outer;
	            }
	          }
	          if (iteratee) {
	            seen.push(computed);
	          }
	          result.push(value);
	        }
	        else if (indexOf(seen, computed) < 0) {
	          if (iteratee || isLarge) {
	            seen.push(computed);
	          }
	          result.push(value);
	        }
	      }
	      return result;
	    }

	    /**
	     * The base implementation of `_.values` and `_.valuesIn` which creates an
	     * array of `object` property values corresponding to the property names
	     * returned by `keysFunc`.
	     *
	     * @private
	     * @param {Object} object The object to query.
	     * @param {Array} props The property names to get values for.
	     * @returns {Object} Returns the array of property values.
	     */
	    function baseValues(object, props) {
	      var index = -1,
	          length = props.length,
	          result = Array(length);

	      while (++index < length) {
	        result[index] = object[props[index]];
	      }
	      return result;
	    }

	    /**
	     * The base implementation of `wrapperValue` which returns the result of
	     * performing a sequence of actions on the unwrapped `value`, where each
	     * successive action is supplied the return value of the previous.
	     *
	     * @private
	     * @param {*} value The unwrapped value.
	     * @param {Array} actions Actions to peform to resolve the unwrapped value.
	     * @returns {*} Returns the resolved unwrapped value.
	     */
	    function baseWrapperValue(value, actions) {
	      var result = value;
	      if (result instanceof LazyWrapper) {
	        result = result.value();
	      }
	      var index = -1,
	          length = actions.length;

	      while (++index < length) {
	        var args = [result],
	            action = actions[index];

	        push.apply(args, action.args);
	        result = action.func.apply(action.thisArg, args);
	      }
	      return result;
	    }

	    /**
	     * Performs a binary search of `array` to determine the index at which `value`
	     * should be inserted into `array` in order to maintain its sort order.
	     *
	     * @private
	     * @param {Array} array The sorted array to inspect.
	     * @param {*} value The value to evaluate.
	     * @param {boolean} [retHighest] Specify returning the highest, instead
	     *  of the lowest, index at which a value should be inserted into `array`.
	     * @returns {number} Returns the index at which `value` should be inserted
	     *  into `array`.
	     */
	    function binaryIndex(array, value, retHighest) {
	      var low = 0,
	          high = array ? array.length : low;

	      if (typeof value == 'number' && value === value && high <= HALF_MAX_ARRAY_LENGTH) {
	        while (low < high) {
	          var mid = (low + high) >>> 1,
	              computed = array[mid];

	          if (retHighest ? (computed <= value) : (computed < value)) {
	            low = mid + 1;
	          } else {
	            high = mid;
	          }
	        }
	        return high;
	      }
	      return binaryIndexBy(array, value, identity, retHighest);
	    }

	    /**
	     * This function is like `binaryIndex` except that it invokes `iteratee` for
	     * `value` and each element of `array` to compute their sort ranking. The
	     * iteratee is invoked with one argument; (value).
	     *
	     * @private
	     * @param {Array} array The sorted array to inspect.
	     * @param {*} value The value to evaluate.
	     * @param {Function} iteratee The function invoked per iteration.
	     * @param {boolean} [retHighest] Specify returning the highest, instead
	     *  of the lowest, index at which a value should be inserted into `array`.
	     * @returns {number} Returns the index at which `value` should be inserted
	     *  into `array`.
	     */
	    function binaryIndexBy(array, value, iteratee, retHighest) {
	      value = iteratee(value);

	      var low = 0,
	          high = array ? array.length : 0,
	          valIsNaN = value !== value,
	          valIsUndef = typeof value == 'undefined';

	      while (low < high) {
	        var mid = floor((low + high) / 2),
	            computed = iteratee(array[mid]),
	            isReflexive = computed === computed;

	        if (valIsNaN) {
	          var setLow = isReflexive || retHighest;
	        } else if (valIsUndef) {
	          setLow = isReflexive && (retHighest || typeof computed != 'undefined');
	        } else {
	          setLow = retHighest ? (computed <= value) : (computed < value);
	        }
	        if (setLow) {
	          low = mid + 1;
	        } else {
	          high = mid;
	        }
	      }
	      return nativeMin(high, MAX_ARRAY_INDEX);
	    }

	    /**
	     * A specialized version of `baseCallback` which only supports `this` binding
	     * and specifying the number of arguments to provide to `func`.
	     *
	     * @private
	     * @param {Function} func The function to bind.
	     * @param {*} thisArg The `this` binding of `func`.
	     * @param {number} [argCount] The number of arguments to provide to `func`.
	     * @returns {Function} Returns the callback.
	     */
	    function bindCallback(func, thisArg, argCount) {
	      if (typeof func != 'function') {
	        return identity;
	      }
	      if (typeof thisArg == 'undefined') {
	        return func;
	      }
	      switch (argCount) {
	        case 1: return function(value) {
	          return func.call(thisArg, value);
	        };
	        case 3: return function(value, index, collection) {
	          return func.call(thisArg, value, index, collection);
	        };
	        case 4: return function(accumulator, value, index, collection) {
	          return func.call(thisArg, accumulator, value, index, collection);
	        };
	        case 5: return function(value, other, key, object, source) {
	          return func.call(thisArg, value, other, key, object, source);
	        };
	      }
	      return function() {
	        return func.apply(thisArg, arguments);
	      };
	    }

	    /**
	     * Creates a clone of the given array buffer.
	     *
	     * @private
	     * @param {ArrayBuffer} buffer The array buffer to clone.
	     * @returns {ArrayBuffer} Returns the cloned array buffer.
	     */
	    function bufferClone(buffer) {
	      return bufferSlice.call(buffer, 0);
	    }
	    if (!bufferSlice) {
	      // PhantomJS has `ArrayBuffer` and `Uint8Array` but not `Float64Array`.
	      bufferClone = !(ArrayBuffer && Uint8Array) ? constant(null) : function(buffer) {
	        var byteLength = buffer.byteLength,
	            floatLength = Float64Array ? floor(byteLength / FLOAT64_BYTES_PER_ELEMENT) : 0,
	            offset = floatLength * FLOAT64_BYTES_PER_ELEMENT,
	            result = new ArrayBuffer(byteLength);

	        if (floatLength) {
	          var view = new Float64Array(result, 0, floatLength);
	          view.set(new Float64Array(buffer, 0, floatLength));
	        }
	        if (byteLength != offset) {
	          view = new Uint8Array(result, offset);
	          view.set(new Uint8Array(buffer, offset));
	        }
	        return result;
	      };
	    }

	    /**
	     * Creates an array that is the composition of partially applied arguments,
	     * placeholders, and provided arguments into a single array of arguments.
	     *
	     * @private
	     * @param {Array|Object} args The provided arguments.
	     * @param {Array} partials The arguments to prepend to those provided.
	     * @param {Array} holders The `partials` placeholder indexes.
	     * @returns {Array} Returns the new array of composed arguments.
	     */
	    function composeArgs(args, partials, holders) {
	      var holdersLength = holders.length,
	          argsIndex = -1,
	          argsLength = nativeMax(args.length - holdersLength, 0),
	          leftIndex = -1,
	          leftLength = partials.length,
	          result = Array(argsLength + leftLength);

	      while (++leftIndex < leftLength) {
	        result[leftIndex] = partials[leftIndex];
	      }
	      while (++argsIndex < holdersLength) {
	        result[holders[argsIndex]] = args[argsIndex];
	      }
	      while (argsLength--) {
	        result[leftIndex++] = args[argsIndex++];
	      }
	      return result;
	    }

	    /**
	     * This function is like `composeArgs` except that the arguments composition
	     * is tailored for `_.partialRight`.
	     *
	     * @private
	     * @param {Array|Object} args The provided arguments.
	     * @param {Array} partials The arguments to append to those provided.
	     * @param {Array} holders The `partials` placeholder indexes.
	     * @returns {Array} Returns the new array of composed arguments.
	     */
	    function composeArgsRight(args, partials, holders) {
	      var holdersIndex = -1,
	          holdersLength = holders.length,
	          argsIndex = -1,
	          argsLength = nativeMax(args.length - holdersLength, 0),
	          rightIndex = -1,
	          rightLength = partials.length,
	          result = Array(argsLength + rightLength);

	      while (++argsIndex < argsLength) {
	        result[argsIndex] = args[argsIndex];
	      }
	      var pad = argsIndex;
	      while (++rightIndex < rightLength) {
	        result[pad + rightIndex] = partials[rightIndex];
	      }
	      while (++holdersIndex < holdersLength) {
	        result[pad + holders[holdersIndex]] = args[argsIndex++];
	      }
	      return result;
	    }

	    /**
	     * Creates a function that aggregates a collection, creating an accumulator
	     * object composed from the results of running each element in the collection
	     * through an iteratee. The `setter` sets the keys and values of the accumulator
	     * object. If `initializer` is provided initializes the accumulator object.
	     *
	     * @private
	     * @param {Function} setter The function to set keys and values of the accumulator object.
	     * @param {Function} [initializer] The function to initialize the accumulator object.
	     * @returns {Function} Returns the new aggregator function.
	     */
	    function createAggregator(setter, initializer) {
	      return function(collection, iteratee, thisArg) {
	        var result = initializer ? initializer() : {};
	        iteratee = getCallback(iteratee, thisArg, 3);

	        if (isArray(collection)) {
	          var index = -1,
	              length = collection.length;

	          while (++index < length) {
	            var value = collection[index];
	            setter(result, value, iteratee(value, index, collection), collection);
	          }
	        } else {
	          baseEach(collection, function(value, key, collection) {
	            setter(result, value, iteratee(value, key, collection), collection);
	          });
	        }
	        return result;
	      };
	    }

	    /**
	     * Creates a function that assigns properties of source object(s) to a given
	     * destination object.
	     *
	     * @private
	     * @param {Function} assigner The function to assign values.
	     * @returns {Function} Returns the new assigner function.
	     */
	    function createAssigner(assigner) {
	      return function() {
	        var length = arguments.length,
	            object = arguments[0];

	        if (length < 2 || object == null) {
	          return object;
	        }
	        if (length > 3 && isIterateeCall(arguments[1], arguments[2], arguments[3])) {
	          length = 2;
	        }
	        // Juggle arguments.
	        if (length > 3 && typeof arguments[length - 2] == 'function') {
	          var customizer = bindCallback(arguments[--length - 1], arguments[length--], 5);
	        } else if (length > 2 && typeof arguments[length - 1] == 'function') {
	          customizer = arguments[--length];
	        }
	        var index = 0;
	        while (++index < length) {
	          var source = arguments[index];
	          if (source) {
	            assigner(object, source, customizer);
	          }
	        }
	        return object;
	      };
	    }

	    /**
	     * Creates a function that wraps `func` and invokes it with the `this`
	     * binding of `thisArg`.
	     *
	     * @private
	     * @param {Function} func The function to bind.
	     * @param {*} [thisArg] The `this` binding of `func`.
	     * @returns {Function} Returns the new bound function.
	     */
	    function createBindWrapper(func, thisArg) {
	      var Ctor = createCtorWrapper(func);

	      function wrapper() {
	        return (this instanceof wrapper ? Ctor : func).apply(thisArg, arguments);
	      }
	      return wrapper;
	    }

	    /**
	     * Creates a `Set` cache object to optimize linear searches of large arrays.
	     *
	     * @private
	     * @param {Array} [values] The values to cache.
	     * @returns {null|Object} Returns the new cache object if `Set` is supported, else `null`.
	     */
	    var createCache = !(nativeCreate && Set) ? constant(null) : function(values) {
	      return new SetCache(values);
	    };

	    /**
	     * Creates a function that produces compound words out of the words in a
	     * given string.
	     *
	     * @private
	     * @param {Function} callback The function to combine each word.
	     * @returns {Function} Returns the new compounder function.
	     */
	    function createCompounder(callback) {
	      return function(string) {
	        var index = -1,
	            array = words(deburr(string)),
	            length = array.length,
	            result = '';

	        while (++index < length) {
	          result = callback(result, array[index], index);
	        }
	        return result;
	      };
	    }

	    /**
	     * Creates a function that produces an instance of `Ctor` regardless of
	     * whether it was invoked as part of a `new` expression or by `call` or `apply`.
	     *
	     * @private
	     * @param {Function} Ctor The constructor to wrap.
	     * @returns {Function} Returns the new wrapped function.
	     */
	    function createCtorWrapper(Ctor) {
	      return function() {
	        var thisBinding = baseCreate(Ctor.prototype),
	            result = Ctor.apply(thisBinding, arguments);

	        // Mimic the constructor's `return` behavior.
	        // See https://es5.github.io/#x13.2.2 for more details.
	        return isObject(result) ? result : thisBinding;
	      };
	    }

	    /**
	     * Creates a function that gets the extremum value of a collection.
	     *
	     * @private
	     * @param {Function} arrayFunc The function to get the extremum value from an array.
	     * @param {boolean} [isMin] Specify returning the minimum, instead of the maximum,
	     *  extremum value.
	     * @returns {Function} Returns the new extremum function.
	     */
	    function createExtremum(arrayFunc, isMin) {
	      return function(collection, iteratee, thisArg) {
	        if (thisArg && isIterateeCall(collection, iteratee, thisArg)) {
	          iteratee = null;
	        }
	        var func = getCallback(),
	            noIteratee = iteratee == null;

	        if (!(func === baseCallback && noIteratee)) {
	          noIteratee = false;
	          iteratee = func(iteratee, thisArg, 3);
	        }
	        if (noIteratee) {
	          var isArr = isArray(collection);
	          if (!isArr && isString(collection)) {
	            iteratee = charAtCallback;
	          } else {
	            return arrayFunc(isArr ? collection : toIterable(collection));
	          }
	        }
	        return extremumBy(collection, iteratee, isMin);
	      };
	    }

	    /**
	     * Creates a function that wraps `func` and invokes it with optional `this`
	     * binding of, partial application, and currying.
	     *
	     * @private
	     * @param {Function|string} func The function or method name to reference.
	     * @param {number} bitmask The bitmask of flags. See `createWrapper` for more details.
	     * @param {*} [thisArg] The `this` binding of `func`.
	     * @param {Array} [partials] The arguments to prepend to those provided to the new function.
	     * @param {Array} [holders] The `partials` placeholder indexes.
	     * @param {Array} [partialsRight] The arguments to append to those provided to the new function.
	     * @param {Array} [holdersRight] The `partialsRight` placeholder indexes.
	     * @param {Array} [argPos] The argument positions of the new function.
	     * @param {number} [ary] The arity cap of `func`.
	     * @param {number} [arity] The arity of `func`.
	     * @returns {Function} Returns the new wrapped function.
	     */
	    function createHybridWrapper(func, bitmask, thisArg, partials, holders, partialsRight, holdersRight, argPos, ary, arity) {
	      var isAry = bitmask & ARY_FLAG,
	          isBind = bitmask & BIND_FLAG,
	          isBindKey = bitmask & BIND_KEY_FLAG,
	          isCurry = bitmask & CURRY_FLAG,
	          isCurryBound = bitmask & CURRY_BOUND_FLAG,
	          isCurryRight = bitmask & CURRY_RIGHT_FLAG;

	      var Ctor = !isBindKey && createCtorWrapper(func),
	          key = func;

	      function wrapper() {
	        // Avoid `arguments` object use disqualifying optimizations by
	        // converting it to an array before providing it to other functions.
	        var length = arguments.length,
	            index = length,
	            args = Array(length);

	        while (index--) {
	          args[index] = arguments[index];
	        }
	        if (partials) {
	          args = composeArgs(args, partials, holders);
	        }
	        if (partialsRight) {
	          args = composeArgsRight(args, partialsRight, holdersRight);
	        }
	        if (isCurry || isCurryRight) {
	          var placeholder = wrapper.placeholder,
	              argsHolders = replaceHolders(args, placeholder);

	          length -= argsHolders.length;
	          if (length < arity) {
	            var newArgPos = argPos ? arrayCopy(argPos) : null,
	                newArity = nativeMax(arity - length, 0),
	                newsHolders = isCurry ? argsHolders : null,
	                newHoldersRight = isCurry ? null : argsHolders,
	                newPartials = isCurry ? args : null,
	                newPartialsRight = isCurry ? null : args;

	            bitmask |= (isCurry ? PARTIAL_FLAG : PARTIAL_RIGHT_FLAG);
	            bitmask &= ~(isCurry ? PARTIAL_RIGHT_FLAG : PARTIAL_FLAG);

	            if (!isCurryBound) {
	              bitmask &= ~(BIND_FLAG | BIND_KEY_FLAG);
	            }
	            var result = createHybridWrapper(func, bitmask, thisArg, newPartials, newsHolders, newPartialsRight, newHoldersRight, newArgPos, ary, newArity);
	            result.placeholder = placeholder;
	            return result;
	          }
	        }
	        var thisBinding = isBind ? thisArg : this;
	        if (isBindKey) {
	          func = thisBinding[key];
	        }
	        if (argPos) {
	          args = reorder(args, argPos);
	        }
	        if (isAry && ary < args.length) {
	          args.length = ary;
	        }
	        return (this instanceof wrapper ? (Ctor || createCtorWrapper(func)) : func).apply(thisBinding, args);
	      }
	      return wrapper;
	    }

	    /**
	     * Creates the pad required for `string` based on the given padding length.
	     * The `chars` string may be truncated if the number of padding characters
	     * exceeds the padding length.
	     *
	     * @private
	     * @param {string} string The string to create padding for.
	     * @param {number} [length=0] The padding length.
	     * @param {string} [chars=' '] The string used as padding.
	     * @returns {string} Returns the pad for `string`.
	     */
	    function createPad(string, length, chars) {
	      var strLength = string.length;
	      length = +length;

	      if (strLength >= length || !nativeIsFinite(length)) {
	        return '';
	      }
	      var padLength = length - strLength;
	      chars = chars == null ? ' ' : (chars + '');
	      return repeat(chars, ceil(padLength / chars.length)).slice(0, padLength);
	    }

	    /**
	     * Creates a function that wraps `func` and invokes it with the optional `this`
	     * binding of `thisArg` and the `partials` prepended to those provided to
	     * the wrapper.
	     *
	     * @private
	     * @param {Function} func The function to partially apply arguments to.
	     * @param {number} bitmask The bitmask of flags. See `createWrapper` for more details.
	     * @param {*} thisArg The `this` binding of `func`.
	     * @param {Array} partials The arguments to prepend to those provided to the new function.
	     * @returns {Function} Returns the new bound function.
	     */
	    function createPartialWrapper(func, bitmask, thisArg, partials) {
	      var isBind = bitmask & BIND_FLAG,
	          Ctor = createCtorWrapper(func);

	      function wrapper() {
	        // Avoid `arguments` object use disqualifying optimizations by
	        // converting it to an array before providing it `func`.
	        var argsIndex = -1,
	            argsLength = arguments.length,
	            leftIndex = -1,
	            leftLength = partials.length,
	            args = Array(argsLength + leftLength);

	        while (++leftIndex < leftLength) {
	          args[leftIndex] = partials[leftIndex];
	        }
	        while (argsLength--) {
	          args[leftIndex++] = arguments[++argsIndex];
	        }
	        return (this instanceof wrapper ? Ctor : func).apply(isBind ? thisArg : this, args);
	      }
	      return wrapper;
	    }

	    /**
	     * Creates a function that either curries or invokes `func` with optional
	     * `this` binding and partially applied arguments.
	     *
	     * @private
	     * @param {Function|string} func The function or method name to reference.
	     * @param {number} bitmask The bitmask of flags.
	     *  The bitmask may be composed of the following flags:
	     *     1 - `_.bind`
	     *     2 - `_.bindKey`
	     *     4 - `_.curry` or `_.curryRight` of a bound function
	     *     8 - `_.curry`
	     *    16 - `_.curryRight`
	     *    32 - `_.partial`
	     *    64 - `_.partialRight`
	     *   128 - `_.rearg`
	     *   256 - `_.ary`
	     * @param {*} [thisArg] The `this` binding of `func`.
	     * @param {Array} [partials] The arguments to be partially applied.
	     * @param {Array} [holders] The `partials` placeholder indexes.
	     * @param {Array} [argPos] The argument positions of the new function.
	     * @param {number} [ary] The arity cap of `func`.
	     * @param {number} [arity] The arity of `func`.
	     * @returns {Function} Returns the new wrapped function.
	     */
	    function createWrapper(func, bitmask, thisArg, partials, holders, argPos, ary, arity) {
	      var isBindKey = bitmask & BIND_KEY_FLAG;
	      if (!isBindKey && !isFunction(func)) {
	        throw new TypeError(FUNC_ERROR_TEXT);
	      }
	      var length = partials ? partials.length : 0;
	      if (!length) {
	        bitmask &= ~(PARTIAL_FLAG | PARTIAL_RIGHT_FLAG);
	        partials = holders = null;
	      }
	      length -= (holders ? holders.length : 0);
	      if (bitmask & PARTIAL_RIGHT_FLAG) {
	        var partialsRight = partials,
	            holdersRight = holders;

	        partials = holders = null;
	      }
	      var data = !isBindKey && getData(func),
	          newData = [func, bitmask, thisArg, partials, holders, partialsRight, holdersRight, argPos, ary, arity];

	      if (data && data !== true) {
	        mergeData(newData, data);
	        bitmask = newData[1];
	        arity = newData[9];
	      }
	      newData[9] = arity == null
	        ? (isBindKey ? 0 : func.length)
	        : (nativeMax(arity - length, 0) || 0);

	      if (bitmask == BIND_FLAG) {
	        var result = createBindWrapper(newData[0], newData[2]);
	      } else if ((bitmask == PARTIAL_FLAG || bitmask == (BIND_FLAG | PARTIAL_FLAG)) && !newData[4].length) {
	        result = createPartialWrapper.apply(null, newData);
	      } else {
	        result = createHybridWrapper.apply(null, newData);
	      }
	      var setter = data ? baseSetData : setData;
	      return setter(result, newData);
	    }

	    /**
	     * A specialized version of `baseIsEqualDeep` for arrays with support for
	     * partial deep comparisons.
	     *
	     * @private
	     * @param {Array} array The array to compare.
	     * @param {Array} other The other array to compare.
	     * @param {Function} equalFunc The function to determine equivalents of values.
	     * @param {Function} [customizer] The function to customize comparing arrays.
	     * @param {boolean} [isWhere] Specify performing partial comparisons.
	     * @param {Array} [stackA] Tracks traversed `value` objects.
	     * @param {Array} [stackB] Tracks traversed `other` objects.
	     * @returns {boolean} Returns `true` if the arrays are equivalent, else `false`.
	     */
	    function equalArrays(array, other, equalFunc, customizer, isWhere, stackA, stackB) {
	      var index = -1,
	          arrLength = array.length,
	          othLength = other.length,
	          result = true;

	      if (arrLength != othLength && !(isWhere && othLength > arrLength)) {
	        return false;
	      }
	      // Deep compare the contents, ignoring non-numeric properties.
	      while (result && ++index < arrLength) {
	        var arrValue = array[index],
	            othValue = other[index];

	        result = undefined;
	        if (customizer) {
	          result = isWhere
	            ? customizer(othValue, arrValue, index)
	            : customizer(arrValue, othValue, index);
	        }
	        if (typeof result == 'undefined') {
	          // Recursively compare arrays (susceptible to call stack limits).
	          if (isWhere) {
	            var othIndex = othLength;
	            while (othIndex--) {
	              othValue = other[othIndex];
	              result = (arrValue && arrValue === othValue) || equalFunc(arrValue, othValue, customizer, isWhere, stackA, stackB);
	              if (result) {
	                break;
	              }
	            }
	          } else {
	            result = (arrValue && arrValue === othValue) || equalFunc(arrValue, othValue, customizer, isWhere, stackA, stackB);
	          }
	        }
	      }
	      return !!result;
	    }

	    /**
	     * A specialized version of `baseIsEqualDeep` for comparing objects of
	     * the same `toStringTag`.
	     *
	     * **Note:** This function only supports comparing values with tags of
	     * `Boolean`, `Date`, `Error`, `Number`, `RegExp`, or `String`.
	     *
	     * @private
	     * @param {Object} value The object to compare.
	     * @param {Object} other The other object to compare.
	     * @param {string} tag The `toStringTag` of the objects to compare.
	     * @returns {boolean} Returns `true` if the objects are equivalent, else `false`.
	     */
	    function equalByTag(object, other, tag) {
	      switch (tag) {
	        case boolTag:
	        case dateTag:
	          // Coerce dates and booleans to numbers, dates to milliseconds and booleans
	          // to `1` or `0` treating invalid dates coerced to `NaN` as not equal.
	          return +object == +other;

	        case errorTag:
	          return object.name == other.name && object.message == other.message;

	        case numberTag:
	          // Treat `NaN` vs. `NaN` as equal.
	          return (object != +object)
	            ? other != +other
	            // But, treat `-0` vs. `+0` as not equal.
	            : (object == 0 ? ((1 / object) == (1 / other)) : object == +other);

	        case regexpTag:
	        case stringTag:
	          // Coerce regexes to strings and treat strings primitives and string
	          // objects as equal. See https://es5.github.io/#x15.10.6.4 for more details.
	          return object == (other + '');
	      }
	      return false;
	    }

	    /**
	     * A specialized version of `baseIsEqualDeep` for objects with support for
	     * partial deep comparisons.
	     *
	     * @private
	     * @param {Object} object The object to compare.
	     * @param {Object} other The other object to compare.
	     * @param {Function} equalFunc The function to determine equivalents of values.
	     * @param {Function} [customizer] The function to customize comparing values.
	     * @param {boolean} [isWhere] Specify performing partial comparisons.
	     * @param {Array} [stackA] Tracks traversed `value` objects.
	     * @param {Array} [stackB] Tracks traversed `other` objects.
	     * @returns {boolean} Returns `true` if the objects are equivalent, else `false`.
	     */
	    function equalObjects(object, other, equalFunc, customizer, isWhere, stackA, stackB) {
	      var objProps = keys(object),
	          objLength = objProps.length,
	          othProps = keys(other),
	          othLength = othProps.length;

	      if (objLength != othLength && !isWhere) {
	        return false;
	      }
	      var hasCtor,
	          index = -1;

	      while (++index < objLength) {
	        var key = objProps[index],
	            result = hasOwnProperty.call(other, key);

	        if (result) {
	          var objValue = object[key],
	              othValue = other[key];

	          result = undefined;
	          if (customizer) {
	            result = isWhere
	              ? customizer(othValue, objValue, key)
	              : customizer(objValue, othValue, key);
	          }
	          if (typeof result == 'undefined') {
	            // Recursively compare objects (susceptible to call stack limits).
	            result = (objValue && objValue === othValue) || equalFunc(objValue, othValue, customizer, isWhere, stackA, stackB);
	          }
	        }
	        if (!result) {
	          return false;
	        }
	        hasCtor || (hasCtor = key == 'constructor');
	      }
	      if (!hasCtor) {
	        var objCtor = object.constructor,
	            othCtor = other.constructor;

	        // Non `Object` object instances with different constructors are not equal.
	        if (objCtor != othCtor && ('constructor' in object && 'constructor' in other) &&
	            !(typeof objCtor == 'function' && objCtor instanceof objCtor && typeof othCtor == 'function' && othCtor instanceof othCtor)) {
	          return false;
	        }
	      }
	      return true;
	    }

	    /**
	     * Gets the extremum value of `collection` invoking `iteratee` for each value
	     * in `collection` to generate the criterion by which the value is ranked.
	     * The `iteratee` is invoked with three arguments; (value, index, collection).
	     *
	     * @private
	     * @param {Array|Object|string} collection The collection to iterate over.
	     * @param {Function} iteratee The function invoked per iteration.
	     * @param {boolean} [isMin] Specify returning the minimum, instead of the
	     *  maximum, extremum value.
	     * @returns {*} Returns the extremum value.
	     */
	    function extremumBy(collection, iteratee, isMin) {
	      var exValue = isMin ? POSITIVE_INFINITY : NEGATIVE_INFINITY,
	          computed = exValue,
	          result = computed;

	      baseEach(collection, function(value, index, collection) {
	        var current = iteratee(value, index, collection);
	        if ((isMin ? current < computed : current > computed) || (current === exValue && current === result)) {
	          computed = current;
	          result = value;
	        }
	      });
	      return result;
	    }

	    /**
	     * Gets the appropriate "callback" function. If the `_.callback` method is
	     * customized this function returns the custom method, otherwise it returns
	     * the `baseCallback` function. If arguments are provided the chosen function
	     * is invoked with them and its result is returned.
	     *
	     * @private
	     * @returns {Function} Returns the chosen function or its result.
	     */
	    function getCallback(func, thisArg, argCount) {
	      var result = lodash.callback || callback;
	      result = result === callback ? baseCallback : result;
	      return argCount ? result(func, thisArg, argCount) : result;
	    }

	    /**
	     * Gets metadata for `func`.
	     *
	     * @private
	     * @param {Function} func The function to query.
	     * @returns {*} Returns the metadata for `func`.
	     */
	    var getData = !metaMap ? noop : function(func) {
	      return metaMap.get(func);
	    };

	    /**
	     * Gets the appropriate "indexOf" function. If the `_.indexOf` method is
	     * customized this function returns the custom method, otherwise it returns
	     * the `baseIndexOf` function. If arguments are provided the chosen function
	     * is invoked with them and its result is returned.
	     *
	     * @private
	     * @returns {Function|number} Returns the chosen function or its result.
	     */
	    function getIndexOf(collection, target, fromIndex) {
	      var result = lodash.indexOf || indexOf;
	      result = result === indexOf ? baseIndexOf : result;
	      return collection ? result(collection, target, fromIndex) : result;
	    }

	    /**
	     * Gets the view, applying any `transforms` to the `start` and `end` positions.
	     *
	     * @private
	     * @param {number} start The start of the view.
	     * @param {number} end The end of the view.
	     * @param {Array} [transforms] The transformations to apply to the view.
	     * @returns {Object} Returns an object containing the `start` and `end`
	     *  positions of the view.
	     */
	    function getView(start, end, transforms) {
	      var index = -1,
	          length = transforms ? transforms.length : 0;

	      while (++index < length) {
	        var data = transforms[index],
	            size = data.size;

	        switch (data.type) {
	          case 'drop':      start += size; break;
	          case 'dropRight': end -= size; break;
	          case 'take':      end = nativeMin(end, start + size); break;
	          case 'takeRight': start = nativeMax(start, end - size); break;
	        }
	      }
	      return { 'start': start, 'end': end };
	    }

	    /**
	     * Initializes an array clone.
	     *
	     * @private
	     * @param {Array} array The array to clone.
	     * @returns {Array} Returns the initialized clone.
	     */
	    function initCloneArray(array) {
	      var length = array.length,
	          result = new array.constructor(length);

	      // Add array properties assigned by `RegExp#exec`.
	      if (length && typeof array[0] == 'string' && hasOwnProperty.call(array, 'index')) {
	        result.index = array.index;
	        result.input = array.input;
	      }
	      return result;
	    }

	    /**
	     * Initializes an object clone.
	     *
	     * @private
	     * @param {Object} object The object to clone.
	     * @returns {Object} Returns the initialized clone.
	     */
	    function initCloneObject(object) {
	      var Ctor = object.constructor;
	      if (!(typeof Ctor == 'function' && Ctor instanceof Ctor)) {
	        Ctor = Object;
	      }
	      return new Ctor;
	    }

	    /**
	     * Initializes an object clone based on its `toStringTag`.
	     *
	     * **Note:** This function only supports cloning values with tags of
	     * `Boolean`, `Date`, `Error`, `Number`, `RegExp`, or `String`.
	     *
	     *
	     * @private
	     * @param {Object} object The object to clone.
	     * @param {string} tag The `toStringTag` of the object to clone.
	     * @param {boolean} [isDeep] Specify a deep clone.
	     * @returns {Object} Returns the initialized clone.
	     */
	    function initCloneByTag(object, tag, isDeep) {
	      var Ctor = object.constructor;
	      switch (tag) {
	        case arrayBufferTag:
	          return bufferClone(object);

	        case boolTag:
	        case dateTag:
	          return new Ctor(+object);

	        case float32Tag: case float64Tag:
	        case int8Tag: case int16Tag: case int32Tag:
	        case uint8Tag: case uint8ClampedTag: case uint16Tag: case uint32Tag:
	          var buffer = object.buffer;
	          return new Ctor(isDeep ? bufferClone(buffer) : buffer, object.byteOffset, object.length);

	        case numberTag:
	        case stringTag:
	          return new Ctor(object);

	        case regexpTag:
	          var result = new Ctor(object.source, reFlags.exec(object));
	          result.lastIndex = object.lastIndex;
	      }
	      return result;
	    }

	    /**
	     * Checks if `func` is eligible for `this` binding.
	     *
	     * @private
	     * @param {Function} func The function to check.
	     * @returns {boolean} Returns `true` if `func` is eligible, else `false`.
	     */
	    function isBindable(func) {
	      var support = lodash.support,
	          result = !(support.funcNames ? func.name : support.funcDecomp);

	      if (!result) {
	        var source = fnToString.call(func);
	        if (!support.funcNames) {
	          result = !reFuncName.test(source);
	        }
	        if (!result) {
	          // Check if `func` references the `this` keyword and store the result.
	          result = reThis.test(source) || isNative(func);
	          baseSetData(func, result);
	        }
	      }
	      return result;
	    }

	    /**
	     * Checks if `value` is a valid array-like index.
	     *
	     * @private
	     * @param {*} value The value to check.
	     * @param {number} [length=MAX_SAFE_INTEGER] The upper bounds of a valid index.
	     * @returns {boolean} Returns `true` if `value` is a valid index, else `false`.
	     */
	    function isIndex(value, length) {
	      value = +value;
	      length = length == null ? MAX_SAFE_INTEGER : length;
	      return value > -1 && value % 1 == 0 && value < length;
	    }

	    /**
	     * Checks if the provided arguments are from an iteratee call.
	     *
	     * @private
	     * @param {*} value The potential iteratee value argument.
	     * @param {*} index The potential iteratee index or key argument.
	     * @param {*} object The potential iteratee object argument.
	     * @returns {boolean} Returns `true` if the arguments are from an iteratee call, else `false`.
	     */
	    function isIterateeCall(value, index, object) {
	      if (!isObject(object)) {
	        return false;
	      }
	      var type = typeof index;
	      if (type == 'number') {
	        var length = object.length,
	            prereq = isLength(length) && isIndex(index, length);
	      } else {
	        prereq = type == 'string' && index in object;
	      }
	      return prereq && object[index] === value;
	    }

	    /**
	     * Checks if `value` is a valid array-like length.
	     *
	     * **Note:** This function is based on ES `ToLength`. See the
	     * [ES spec](https://people.mozilla.org/~jorendorff/es6-draft.html#sec-tolength)
	     * for more details.
	     *
	     * @private
	     * @param {*} value The value to check.
	     * @returns {boolean} Returns `true` if `value` is a valid length, else `false`.
	     */
	    function isLength(value) {
	      return typeof value == 'number' && value > -1 && value % 1 == 0 && value <= MAX_SAFE_INTEGER;
	    }

	    /**
	     * Checks if `value` is suitable for strict equality comparisons, i.e. `===`.
	     *
	     * @private
	     * @param {*} value The value to check.
	     * @returns {boolean} Returns `true` if `value` if suitable for strict
	     *  equality comparisons, else `false`.
	     */
	    function isStrictComparable(value) {
	      return value === value && (value === 0 ? ((1 / value) > 0) : !isObject(value));
	    }

	    /**
	     * Merges the function metadata of `source` into `data`.
	     *
	     * Merging metadata reduces the number of wrappers required to invoke a function.
	     * This is possible because methods like `_.bind`, `_.curry`, and `_.partial`
	     * may be applied regardless of execution order. Methods like `_.ary` and `_.rearg`
	     * augment function arguments, making the order in which they are executed important,
	     * preventing the merging of metadata. However, we make an exception for a safe
	     * common case where curried functions have `_.ary` and or `_.rearg` applied.
	     *
	     * @private
	     * @param {Array} data The destination metadata.
	     * @param {Array} source The source metadata.
	     * @returns {Array} Returns `data`.
	     */
	    function mergeData(data, source) {
	      var bitmask = data[1],
	          srcBitmask = source[1],
	          newBitmask = bitmask | srcBitmask;

	      var arityFlags = ARY_FLAG | REARG_FLAG,
	          bindFlags = BIND_FLAG | BIND_KEY_FLAG,
	          comboFlags = arityFlags | bindFlags | CURRY_BOUND_FLAG | CURRY_RIGHT_FLAG;

	      var isAry = bitmask & ARY_FLAG && !(srcBitmask & ARY_FLAG),
	          isRearg = bitmask & REARG_FLAG && !(srcBitmask & REARG_FLAG),
	          argPos = (isRearg ? data : source)[7],
	          ary = (isAry ? data : source)[8];

	      var isCommon = !(bitmask >= REARG_FLAG && srcBitmask > bindFlags) &&
	        !(bitmask > bindFlags && srcBitmask >= REARG_FLAG);

	      var isCombo = (newBitmask >= arityFlags && newBitmask <= comboFlags) &&
	        (bitmask < REARG_FLAG || ((isRearg || isAry) && argPos.length <= ary));

	      // Exit early if metadata can't be merged.
	      if (!(isCommon || isCombo)) {
	        return data;
	      }
	      // Use source `thisArg` if available.
	      if (srcBitmask & BIND_FLAG) {
	        data[2] = source[2];
	        // Set when currying a bound function.
	        newBitmask |= (bitmask & BIND_FLAG) ? 0 : CURRY_BOUND_FLAG;
	      }
	      // Compose partial arguments.
	      var value = source[3];
	      if (value) {
	        var partials = data[3];
	        data[3] = partials ? composeArgs(partials, value, source[4]) : arrayCopy(value);
	        data[4] = partials ? replaceHolders(data[3], PLACEHOLDER) : arrayCopy(source[4]);
	      }
	      // Compose partial right arguments.
	      value = source[5];
	      if (value) {
	        partials = data[5];
	        data[5] = partials ? composeArgsRight(partials, value, source[6]) : arrayCopy(value);
	        data[6] = partials ? replaceHolders(data[5], PLACEHOLDER) : arrayCopy(source[6]);
	      }
	      // Use source `argPos` if available.
	      value = source[7];
	      if (value) {
	        data[7] = arrayCopy(value);
	      }
	      // Use source `ary` if it's smaller.
	      if (srcBitmask & ARY_FLAG) {
	        data[8] = data[8] == null ? source[8] : nativeMin(data[8], source[8]);
	      }
	      // Use source `arity` if one is not provided.
	      if (data[9] == null) {
	        data[9] = source[9];
	      }
	      // Use source `func` and merge bitmasks.
	      data[0] = source[0];
	      data[1] = newBitmask;

	      return data;
	    }

	    /**
	     * A specialized version of `_.pick` that picks `object` properties specified
	     * by the `props` array.
	     *
	     * @private
	     * @param {Object} object The source object.
	     * @param {string[]} props The property names to pick.
	     * @returns {Object} Returns the new object.
	     */
	    function pickByArray(object, props) {
	      object = toObject(object);

	      var index = -1,
	          length = props.length,
	          result = {};

	      while (++index < length) {
	        var key = props[index];
	        if (key in object) {
	          result[key] = object[key];
	        }
	      }
	      return result;
	    }

	    /**
	     * A specialized version of `_.pick` that picks `object` properties `predicate`
	     * returns truthy for.
	     *
	     * @private
	     * @param {Object} object The source object.
	     * @param {Function} predicate The function invoked per iteration.
	     * @returns {Object} Returns the new object.
	     */
	    function pickByCallback(object, predicate) {
	      var result = {};
	      baseForIn(object, function(value, key, object) {
	        if (predicate(value, key, object)) {
	          result[key] = value;
	        }
	      });
	      return result;
	    }

	    /**
	     * Reorder `array` according to the specified indexes where the element at
	     * the first index is assigned as the first element, the element at
	     * the second index is assigned as the second element, and so on.
	     *
	     * @private
	     * @param {Array} array The array to reorder.
	     * @param {Array} indexes The arranged array indexes.
	     * @returns {Array} Returns `array`.
	     */
	    function reorder(array, indexes) {
	      var arrLength = array.length,
	          length = nativeMin(indexes.length, arrLength),
	          oldArray = arrayCopy(array);

	      while (length--) {
	        var index = indexes[length];
	        array[length] = isIndex(index, arrLength) ? oldArray[index] : undefined;
	      }
	      return array;
	    }

	    /**
	     * Sets metadata for `func`.
	     *
	     * **Note:** If this function becomes hot, i.e. is invoked a lot in a short
	     * period of time, it will trip its breaker and transition to an identity function
	     * to avoid garbage collection pauses in V8. See [V8 issue 2070](https://code.google.com/p/v8/issues/detail?id=2070)
	     * for more details.
	     *
	     * @private
	     * @param {Function} func The function to associate metadata with.
	     * @param {*} data The metadata.
	     * @returns {Function} Returns `func`.
	     */
	    var setData = (function() {
	      var count = 0,
	          lastCalled = 0;

	      return function(key, value) {
	        var stamp = now(),
	            remaining = HOT_SPAN - (stamp - lastCalled);

	        lastCalled = stamp;
	        if (remaining > 0) {
	          if (++count >= HOT_COUNT) {
	            return key;
	          }
	        } else {
	          count = 0;
	        }
	        return baseSetData(key, value);
	      };
	    }());

	    /**
	     * A fallback implementation of `_.isPlainObject` which checks if `value`
	     * is an object created by the `Object` constructor or has a `[[Prototype]]`
	     * of `null`.
	     *
	     * @private
	     * @param {*} value The value to check.
	     * @returns {boolean} Returns `true` if `value` is a plain object, else `false`.
	     */
	    function shimIsPlainObject(value) {
	      var Ctor,
	          support = lodash.support;

	      // Exit early for non `Object` objects.
	      if (!(isObjectLike(value) && objToString.call(value) == objectTag) ||
	          (!hasOwnProperty.call(value, 'constructor') &&
	            (Ctor = value.constructor, typeof Ctor == 'function' && !(Ctor instanceof Ctor)))) {
	        return false;
	      }
	      // IE < 9 iterates inherited properties before own properties. If the first
	      // iterated property is an object's own property then there are no inherited
	      // enumerable properties.
	      var result;
	      // In most environments an object's own properties are iterated before
	      // its inherited properties. If the last iterated property is an object's
	      // own property then there are no inherited enumerable properties.
	      baseForIn(value, function(subValue, key) {
	        result = key;
	      });
	      return typeof result == 'undefined' || hasOwnProperty.call(value, result);
	    }

	    /**
	     * A fallback implementation of `Object.keys` which creates an array of the
	     * own enumerable property names of `object`.
	     *
	     * @private
	     * @param {Object} object The object to inspect.
	     * @returns {Array} Returns the array of property names.
	     */
	    function shimKeys(object) {
	      var props = keysIn(object),
	          propsLength = props.length,
	          length = propsLength && object.length,
	          support = lodash.support;

	      var allowIndexes = length && isLength(length) &&
	        (isArray(object) || (support.nonEnumArgs && isArguments(object)));

	      var index = -1,
	          result = [];

	      while (++index < propsLength) {
	        var key = props[index];
	        if ((allowIndexes && isIndex(key, length)) || hasOwnProperty.call(object, key)) {
	          result.push(key);
	        }
	      }
	      return result;
	    }

	    /**
	     * Converts `value` to an array-like object if it is not one.
	     *
	     * @private
	     * @param {*} value The value to process.
	     * @returns {Array|Object} Returns the array-like object.
	     */
	    function toIterable(value) {
	      if (value == null) {
	        return [];
	      }
	      if (!isLength(value.length)) {
	        return values(value);
	      }
	      return isObject(value) ? value : Object(value);
	    }

	    /**
	     * Converts `value` to an object if it is not one.
	     *
	     * @private
	     * @param {*} value The value to process.
	     * @returns {Object} Returns the object.
	     */
	    function toObject(value) {
	      return isObject(value) ? value : Object(value);
	    }

	    /*------------------------------------------------------------------------*/

	    /**
	     * Creates an array of elements split into groups the length of `size`.
	     * If `collection` can't be split evenly, the final chunk will be the remaining
	     * elements.
	     *
	     * @static
	     * @memberOf _
	     * @category Array
	     * @param {Array} array The array to process.
	     * @param {numer} [size=1] The length of each chunk.
	     * @param- {Object} [guard] Enables use as a callback for functions like `_.map`.
	     * @returns {Array} Returns the new array containing chunks.
	     * @example
	     *
	     * _.chunk(['a', 'b', 'c', 'd'], 2);
	     * // => [['a', 'b'], ['c', 'd']]
	     *
	     * _.chunk(['a', 'b', 'c', 'd'], 3);
	     * // => [['a', 'b', 'c'], ['d']]
	     */
	    function chunk(array, size, guard) {
	      if (guard ? isIterateeCall(array, size, guard) : size == null) {
	        size = 1;
	      } else {
	        size = nativeMax(+size || 1, 1);
	      }
	      var index = 0,
	          length = array ? array.length : 0,
	          resIndex = -1,
	          result = Array(ceil(length / size));

	      while (index < length) {
	        result[++resIndex] = baseSlice(array, index, (index += size));
	      }
	      return result;
	    }

	    /**
	     * Creates an array with all falsey values removed. The values `false`, `null`,
	     * `0`, `""`, `undefined`, and `NaN` are falsey.
	     *
	     * @static
	     * @memberOf _
	     * @category Array
	     * @param {Array} array The array to compact.
	     * @returns {Array} Returns the new array of filtered values.
	     * @example
	     *
	     * _.compact([0, 1, false, 2, '', 3]);
	     * // => [1, 2, 3]
	     */
	    function compact(array) {
	      var index = -1,
	          length = array ? array.length : 0,
	          resIndex = -1,
	          result = [];

	      while (++index < length) {
	        var value = array[index];
	        if (value) {
	          result[++resIndex] = value;
	        }
	      }
	      return result;
	    }

	    /**
	     * Creates an array excluding all values of the provided arrays using
	     * `SameValueZero` for equality comparisons.
	     *
	     * **Note:** `SameValueZero` comparisons are like strict equality comparisons,
	     * e.g. `===`, except that `NaN` matches `NaN`. See the
	     * [ES spec](https://people.mozilla.org/~jorendorff/es6-draft.html#sec-samevaluezero)
	     * for more details.
	     *
	     * @static
	     * @memberOf _
	     * @category Array
	     * @param {Array} array The array to inspect.
	     * @param {...Array} [values] The arrays of values to exclude.
	     * @returns {Array} Returns the new array of filtered values.
	     * @example
	     *
	     * _.difference([1, 2, 3], [5, 2, 10]);
	     * // => [1, 3]
	     */
	    function difference() {
	      var index = -1,
	          length = arguments.length;

	      while (++index < length) {
	        var value = arguments[index];
	        if (isArray(value) || isArguments(value)) {
	          break;
	        }
	      }
	      return baseDifference(value, baseFlatten(arguments, false, true, ++index));
	    }

	    /**
	     * Creates a slice of `array` with `n` elements dropped from the beginning.
	     *
	     * @static
	     * @memberOf _
	     * @type Function
	     * @category Array
	     * @param {Array} array The array to query.
	     * @param {number} [n=1] The number of elements to drop.
	     * @param- {Object} [guard] Enables use as a callback for functions like `_.map`.
	     * @returns {Array} Returns the slice of `array`.
	     * @example
	     *
	     * _.drop([1, 2, 3]);
	     * // => [2, 3]
	     *
	     * _.drop([1, 2, 3], 2);
	     * // => [3]
	     *
	     * _.drop([1, 2, 3], 5);
	     * // => []
	     *
	     * _.drop([1, 2, 3], 0);
	     * // => [1, 2, 3]
	     */
	    function drop(array, n, guard) {
	      var length = array ? array.length : 0;
	      if (!length) {
	        return [];
	      }
	      if (guard ? isIterateeCall(array, n, guard) : n == null) {
	        n = 1;
	      }
	      return baseSlice(array, n < 0 ? 0 : n);
	    }

	    /**
	     * Creates a slice of `array` with `n` elements dropped from the end.
	     *
	     * @static
	     * @memberOf _
	     * @type Function
	     * @category Array
	     * @param {Array} array The array to query.
	     * @param {number} [n=1] The number of elements to drop.
	     * @param- {Object} [guard] Enables use as a callback for functions like `_.map`.
	     * @returns {Array} Returns the slice of `array`.
	     * @example
	     *
	     * _.dropRight([1, 2, 3]);
	     * // => [1, 2]
	     *
	     * _.dropRight([1, 2, 3], 2);
	     * // => [1]
	     *
	     * _.dropRight([1, 2, 3], 5);
	     * // => []
	     *
	     * _.dropRight([1, 2, 3], 0);
	     * // => [1, 2, 3]
	     */
	    function dropRight(array, n, guard) {
	      var length = array ? array.length : 0;
	      if (!length) {
	        return [];
	      }
	      if (guard ? isIterateeCall(array, n, guard) : n == null) {
	        n = 1;
	      }
	      n = length - (+n || 0);
	      return baseSlice(array, 0, n < 0 ? 0 : n);
	    }

	    /**
	     * Creates a slice of `array` excluding elements dropped from the end.
	     * Elements are dropped until `predicate` returns falsey. The predicate is
	     * bound to `thisArg` and invoked with three arguments; (value, index, array).
	     *
	     * If a property name is provided for `predicate` the created "_.property"
	     * style callback returns the property value of the given element.
	     *
	     * If an object is provided for `predicate` the created "_.matches" style
	     * callback returns `true` for elements that have the properties of the given
	     * object, else `false`.
	     *
	     * @static
	     * @memberOf _
	     * @type Function
	     * @category Array
	     * @param {Array} array The array to query.
	     * @param {Function|Object|string} [predicate=_.identity] The function invoked
	     *  per element.
	     * @param {*} [thisArg] The `this` binding of `predicate`.
	     * @returns {Array} Returns the slice of `array`.
	     * @example
	     *
	     * _.dropRightWhile([1, 2, 3], function(n) { return n > 1; });
	     * // => [1]
	     *
	     * var users = [
	     *   { 'user': 'barney',  'status': 'busy', 'active': false },
	     *   { 'user': 'fred',    'status': 'busy', 'active': true },
	     *   { 'user': 'pebbles', 'status': 'away', 'active': true }
	     * ];
	     *
	     * // using the "_.property" callback shorthand
	     * _.pluck(_.dropRightWhile(users, 'active'), 'user');
	     * // => ['barney']
	     *
	     * // using the "_.matches" callback shorthand
	     * _.pluck(_.dropRightWhile(users, { 'status': 'away' }), 'user');
	     * // => ['barney', 'fred']
	     */
	    function dropRightWhile(array, predicate, thisArg) {
	      var length = array ? array.length : 0;
	      if (!length) {
	        return [];
	      }
	      predicate = getCallback(predicate, thisArg, 3);
	      while (length-- && predicate(array[length], length, array)) {}
	      return baseSlice(array, 0, length + 1);
	    }

	    /**
	     * Creates a slice of `array` excluding elements dropped from the beginning.
	     * Elements are dropped until `predicate` returns falsey. The predicate is
	     * bound to `thisArg` and invoked with three arguments; (value, index, array).
	     *
	     * If a property name is provided for `predicate` the created "_.property"
	     * style callback returns the property value of the given element.
	     *
	     * If an object is provided for `predicate` the created "_.matches" style
	     * callback returns `true` for elements that have the properties of the given
	     * object, else `false`.
	     *
	     * @static
	     * @memberOf _
	     * @type Function
	     * @category Array
	     * @param {Array} array The array to query.
	     * @param {Function|Object|string} [predicate=_.identity] The function invoked
	     *  per element.
	     * @param {*} [thisArg] The `this` binding of `predicate`.
	     * @returns {Array} Returns the slice of `array`.
	     * @example
	     *
	     * _.dropWhile([1, 2, 3], function(n) { return n < 3; });
	     * // => [3]
	     *
	     * var users = [
	     *   { 'user': 'barney',  'status': 'busy', 'active': true },
	     *   { 'user': 'fred',    'status': 'busy', 'active': false },
	     *   { 'user': 'pebbles', 'status': 'away', 'active': true }
	     * ];
	     *
	     * // using the "_.property" callback shorthand
	     * _.pluck(_.dropWhile(users, 'active'), 'user');
	     * // => ['fred', 'pebbles']
	     *
	     * // using the "_.matches" callback shorthand
	     * _.pluck(_.dropWhile(users, { 'status': 'busy' }), 'user');
	     * // => ['pebbles']
	     */
	    function dropWhile(array, predicate, thisArg) {
	      var length = array ? array.length : 0;
	      if (!length) {
	        return [];
	      }
	      var index = -1;
	      predicate = getCallback(predicate, thisArg, 3);
	      while (++index < length && predicate(array[index], index, array)) {}
	      return baseSlice(array, index);
	    }

	    /**
	     * This method is like `_.find` except that it returns the index of the first
	     * element `predicate` returns truthy for, instead of the element itself.
	     *
	     * If a property name is provided for `predicate` the created "_.property"
	     * style callback returns the property value of the given element.
	     *
	     * If an object is provided for `predicate` the created "_.matches" style
	     * callback returns `true` for elements that have the properties of the given
	     * object, else `false`.
	     *
	     * @static
	     * @memberOf _
	     * @category Array
	     * @param {Array} array The array to search.
	     * @param {Function|Object|string} [predicate=_.identity] The function invoked
	     *  per iteration. If a property name or object is provided it is used to
	     *  create a "_.property" or "_.matches" style callback respectively.
	     * @param {*} [thisArg] The `this` binding of `predicate`.
	     * @returns {number} Returns the index of the found element, else `-1`.
	     * @example
	     *
	     * var users = [
	     *   { 'user': 'barney',  'age': 36, 'active': false },
	     *   { 'user': 'fred',    'age': 40, 'active': true },
	     *   { 'user': 'pebbles', 'age': 1,  'active': false }
	     * ];
	     *
	     * _.findIndex(users, function(chr) { return chr.age < 40; });
	     * // => 0
	     *
	     * // using the "_.matches" callback shorthand
	     * _.findIndex(users, { 'age': 1 });
	     * // => 2
	     *
	     * // using the "_.property" callback shorthand
	     * _.findIndex(users, 'active');
	     * // => 1
	     */
	    function findIndex(array, predicate, thisArg) {
	      var index = -1,
	          length = array ? array.length : 0;

	      predicate = getCallback(predicate, thisArg, 3);
	      while (++index < length) {
	        if (predicate(array[index], index, array)) {
	          return index;
	        }
	      }
	      return -1;
	    }

	    /**
	     * This method is like `_.findIndex` except that it iterates over elements
	     * of `collection` from right to left.
	     *
	     * If a property name is provided for `predicate` the created "_.property"
	     * style callback returns the property value of the given element.
	     *
	     * If an object is provided for `predicate` the created "_.matches" style
	     * callback returns `true` for elements that have the properties of the given
	     * object, else `false`.
	     *
	     * @static
	     * @memberOf _
	     * @category Array
	     * @param {Array} array The array to search.
	     * @param {Function|Object|string} [predicate=_.identity] The function invoked
	     *  per iteration. If a property name or object is provided it is used to
	     *  create a "_.property" or "_.matches" style callback respectively.
	     * @param {*} [thisArg] The `this` binding of `predicate`.
	     * @returns {number} Returns the index of the found element, else `-1`.
	     * @example
	     *
	     * var users = [
	     *   { 'user': 'barney',  'age': 36, 'active': true },
	     *   { 'user': 'fred',    'age': 40, 'active': false },
	     *   { 'user': 'pebbles', 'age': 1,  'active': false }
	     * ];
	     *
	     * _.findLastIndex(users, function(chr) { return chr.age < 40; });
	     * // => 2
	     *
	     * // using the "_.matches" callback shorthand
	     * _.findLastIndex(users, { 'age': 40 });
	     * // => 1
	     *
	     * // using the "_.property" callback shorthand
	     * _.findLastIndex(users, 'active');
	     * // => 0
	     */
	    function findLastIndex(array, predicate, thisArg) {
	      var length = array ? array.length : 0;
	      predicate = getCallback(predicate, thisArg, 3);
	      while (length--) {
	        if (predicate(array[length], length, array)) {
	          return length;
	        }
	      }
	      return -1;
	    }

	    /**
	     * Gets the first element of `array`.
	     *
	     * @static
	     * @memberOf _
	     * @alias head
	     * @category Array
	     * @param {Array} array The array to query.
	     * @returns {*} Returns the first element of `array`.
	     * @example
	     *
	     * _.first([1, 2, 3]);
	     * // => 1
	     *
	     * _.first([]);
	     * // => undefined
	     */
	    function first(array) {
	      return array ? array[0] : undefined;
	    }

	    /**
	     * Flattens a nested array. If `isDeep` is `true` the array is recursively
	     * flattened, otherwise it is only flattened a single level.
	     *
	     * @static
	     * @memberOf _
	     * @category Array
	     * @param {Array} array The array to flatten.
	     * @param {boolean} [isDeep] Specify a deep flatten.
	     * @param- {Object} [guard] Enables use as a callback for functions like `_.map`.
	     * @returns {Array} Returns the new flattened array.
	     * @example
	     *
	     * _.flatten([1, [2], [3, [[4]]]]);
	     * // => [1, 2, 3, [[4]]];
	     *
	     * // using `isDeep`
	     * _.flatten([1, [2], [3, [[4]]]], true);
	     * // => [1, 2, 3, 4];
	     */
	    function flatten(array, isDeep, guard) {
	      var length = array ? array.length : 0;
	      if (guard && isIterateeCall(array, isDeep, guard)) {
	        isDeep = false;
	      }
	      return length ? baseFlatten(array, isDeep) : [];
	    }

	    /**
	     * Recursively flattens a nested array.
	     *
	     * @static
	     * @memberOf _
	     * @category Array
	     * @param {Array} array The array to recursively flatten.
	     * @returns {Array} Returns the new flattened array.
	     * @example
	     *
	     * _.flattenDeep([1, [2], [3, [[4]]]]);
	     * // => [1, 2, 3, 4];
	     */
	    function flattenDeep(array) {
	      var length = array ? array.length : 0;
	      return length ? baseFlatten(array, true) : [];
	    }

	    /**
	     * Gets the index at which the first occurrence of `value` is found in `array`
	     * using `SameValueZero` for equality comparisons. If `fromIndex` is negative,
	     * it is used as the offset from the end of `array`. If `array` is sorted
	     * providing `true` for `fromIndex` performs a faster binary search.
	     *
	     * **Note:** `SameValueZero` comparisons are like strict equality comparisons,
	     * e.g. `===`, except that `NaN` matches `NaN`. See the
	     * [ES spec](https://people.mozilla.org/~jorendorff/es6-draft.html#sec-samevaluezero)
	     * for more details.
	     *
	     * @static
	     * @memberOf _
	     * @category Array
	     * @param {Array} array The array to search.
	     * @param {*} value The value to search for.
	     * @param {boolean|number} [fromIndex=0] The index to search from or `true`
	     *  to perform a binary search on a sorted array.
	     * @returns {number} Returns the index of the matched value, else `-1`.
	     * @example
	     *
	     * _.indexOf([1, 2, 3, 1, 2, 3], 2);
	     * // => 1
	     *
	     * // using `fromIndex`
	     * _.indexOf([1, 2, 3, 1, 2, 3], 2, 3);
	     * // => 4
	     *
	     * // performing a binary search
	     * _.indexOf([4, 4, 5, 5, 6, 6], 5, true);
	     * // => 2
	     */
	    function indexOf(array, value, fromIndex) {
	      var length = array ? array.length : 0;
	      if (!length) {
	        return -1;
	      }
	      if (typeof fromIndex == 'number') {
	        fromIndex = fromIndex < 0 ? nativeMax(length + fromIndex, 0) : (fromIndex || 0);
	      } else if (fromIndex) {
	        var index = binaryIndex(array, value),
	            other = array[index];

	        return (value === value ? value === other : other !== other) ? index : -1;
	      }
	      return baseIndexOf(array, value, fromIndex);
	    }

	    /**
	     * Gets all but the last element of `array`.
	     *
	     * @static
	     * @memberOf _
	     * @category Array
	     * @param {Array} array The array to query.
	     * @returns {Array} Returns the slice of `array`.
	     * @example
	     *
	     * _.initial([1, 2, 3]);
	     * // => [1, 2]
	     */
	    function initial(array) {
	      return dropRight(array, 1);
	    }

	    /**
	     * Creates an array of unique values in all provided arrays using `SameValueZero`
	     * for equality comparisons.
	     *
	     * **Note:** `SameValueZero` comparisons are like strict equality comparisons,
	     * e.g. `===`, except that `NaN` matches `NaN`. See the
	     * [ES spec](https://people.mozilla.org/~jorendorff/es6-draft.html#sec-samevaluezero)
	     * for more details.
	     *
	     * @static
	     * @memberOf _
	     * @category Array
	     * @param {...Array} [arrays] The arrays to inspect.
	     * @returns {Array} Returns the new array of shared values.
	     * @example
	     *
	     * _.intersection([1, 2, 3], [5, 2, 1, 4], [2, 1]);
	     * // => [1, 2]
	     */
	    function intersection() {
	      var args = [],
	          argsIndex = -1,
	          argsLength = arguments.length,
	          caches = [],
	          indexOf = getIndexOf(),
	          isCommon = indexOf == baseIndexOf;

	      while (++argsIndex < argsLength) {
	        var value = arguments[argsIndex];
	        if (isArray(value) || isArguments(value)) {
	          args.push(value);
	          caches.push(isCommon && value.length >= 120 && createCache(argsIndex && value));
	        }
	      }
	      argsLength = args.length;
	      var array = args[0],
	          index = -1,
	          length = array ? array.length : 0,
	          result = [],
	          seen = caches[0];

	      outer:
	      while (++index < length) {
	        value = array[index];
	        if ((seen ? cacheIndexOf(seen, value) : indexOf(result, value)) < 0) {
	          argsIndex = argsLength;
	          while (--argsIndex) {
	            var cache = caches[argsIndex];
	            if ((cache ? cacheIndexOf(cache, value) : indexOf(args[argsIndex], value)) < 0) {
	              continue outer;
	            }
	          }
	          if (seen) {
	            seen.push(value);
	          }
	          result.push(value);
	        }
	      }
	      return result;
	    }

	    /**
	     * Gets the last element of `array`.
	     *
	     * @static
	     * @memberOf _
	     * @category Array
	     * @param {Array} array The array to query.
	     * @returns {*} Returns the last element of `array`.
	     * @example
	     *
	     * _.last([1, 2, 3]);
	     * // => 3
	     */
	    function last(array) {
	      var length = array ? array.length : 0;
	      return length ? array[length - 1] : undefined;
	    }

	    /**
	     * This method is like `_.indexOf` except that it iterates over elements of
	     * `array` from right to left.
	     *
	     * @static
	     * @memberOf _
	     * @category Array
	     * @param {Array} array The array to search.
	     * @param {*} value The value to search for.
	     * @param {boolean|number} [fromIndex=array.length-1] The index to search from
	     *  or `true` to perform a binary search on a sorted array.
	     * @returns {number} Returns the index of the matched value, else `-1`.
	     * @example
	     *
	     * _.lastIndexOf([1, 2, 3, 1, 2, 3], 2);
	     * // => 4
	     *
	     * // using `fromIndex`
	     * _.lastIndexOf([1, 2, 3, 1, 2, 3], 2, 3);
	     * // => 1
	     *
	     * // performing a binary search
	     * _.lastIndexOf([4, 4, 5, 5, 6, 6], 5, true);
	     * // => 3
	     */
	    function lastIndexOf(array, value, fromIndex) {
	      var length = array ? array.length : 0;
	      if (!length) {
	        return -1;
	      }
	      var index = length;
	      if (typeof fromIndex == 'number') {
	        index = (fromIndex < 0 ? nativeMax(length + fromIndex, 0) : nativeMin(fromIndex || 0, length - 1)) + 1;
	      } else if (fromIndex) {
	        index = binaryIndex(array, value, true) - 1;
	        var other = array[index];
	        return (value === value ? value === other : other !== other) ? index : -1;
	      }
	      if (value !== value) {
	        return indexOfNaN(array, index, true);
	      }
	      while (index--) {
	        if (array[index] === value) {
	          return index;
	        }
	      }
	      return -1;
	    }

	    /**
	     * Removes all provided values from `array` using `SameValueZero` for equality
	     * comparisons.
	     *
	     * **Notes:**
	     *  - Unlike `_.without`, this method mutates `array`.
	     *  - `SameValueZero` comparisons are like strict equality comparisons, e.g. `===`,
	     *    except that `NaN` matches `NaN`. See the [ES spec](https://people.mozilla.org/~jorendorff/es6-draft.html#sec-samevaluezero)
	     *    for more details.
	     *
	     * @static
	     * @memberOf _
	     * @category Array
	     * @param {Array} array The array to modify.
	     * @param {...*} [values] The values to remove.
	     * @returns {Array} Returns `array`.
	     * @example
	     *
	     * var array = [1, 2, 3, 1, 2, 3];
	     * _.pull(array, 2, 3);
	     * console.log(array);
	     * // => [1, 1]
	     */
	    function pull() {
	      var array = arguments[0];
	      if (!(array && array.length)) {
	        return array;
	      }
	      var index = 0,
	          indexOf = getIndexOf(),
	          length = arguments.length;

	      while (++index < length) {
	        var fromIndex = 0,
	            value = arguments[index];

	        while ((fromIndex = indexOf(array, value, fromIndex)) > -1) {
	          splice.call(array, fromIndex, 1);
	        }
	      }
	      return array;
	    }

	    /**
	     * Removes elements from `array` corresponding to the given indexes and returns
	     * an array of the removed elements. Indexes may be specified as an array of
	     * indexes or as individual arguments.
	     *
	     * **Note:** Unlike `_.at`, this method mutates `array`.
	     *
	     * @static
	     * @memberOf _
	     * @category Array
	     * @param {Array} array The array to modify.
	     * @param {...(number|number[])} [indexes] The indexes of elements to remove,
	     *  specified as individual indexes or arrays of indexes.
	     * @returns {Array} Returns the new array of removed elements.
	     * @example
	     *
	     * var array = [5, 10, 15, 20];
	     * var evens = _.pullAt(array, [1, 3]);
	     *
	     * console.log(array);
	     * // => [5, 15]
	     *
	     * console.log(evens);
	     * // => [10, 20]
	     */
	    function pullAt(array) {
	      return basePullAt(array || [], baseFlatten(arguments, false, false, 1));
	    }

	    /**
	     * Removes all elements from `array` that `predicate` returns truthy for
	     * and returns an array of the removed elements. The predicate is bound to
	     * `thisArg` and invoked with three arguments; (value, index, array).
	     *
	     * If a property name is provided for `predicate` the created "_.property"
	     * style callback returns the property value of the given element.
	     *
	     * If an object is provided for `predicate` the created "_.matches" style
	     * callback returns `true` for elements that have the properties of the given
	     * object, else `false`.
	     *
	     * **Note:** Unlike `_.filter`, this method mutates `array`.
	     *
	     * @static
	     * @memberOf _
	     * @category Array
	     * @param {Array} array The array to modify.
	     * @param {Function|Object|string} [predicate=_.identity] The function invoked
	     *  per iteration. If a property name or object is provided it is used to
	     *  create a "_.property" or "_.matches" style callback respectively.
	     * @param {*} [thisArg] The `this` binding of `predicate`.
	     * @returns {Array} Returns the new array of removed elements.
	     * @example
	     *
	     * var array = [1, 2, 3, 4];
	     * var evens = _.remove(array, function(n) { return n % 2 == 0; });
	     *
	     * console.log(array);
	     * // => [1, 3]
	     *
	     * console.log(evens);
	     * // => [2, 4]
	     */
	    function remove(array, predicate, thisArg) {
	      var index = -1,
	          length = array ? array.length : 0,
	          result = [];

	      predicate = getCallback(predicate, thisArg, 3);
	      while (++index < length) {
	        var value = array[index];
	        if (predicate(value, index, array)) {
	          result.push(value);
	          splice.call(array, index--, 1);
	          length--;
	        }
	      }
	      return result;
	    }

	    /**
	     * Gets all but the first element of `array`.
	     *
	     * @static
	     * @memberOf _
	     * @alias tail
	     * @category Array
	     * @param {Array} array The array to query.
	     * @returns {Array} Returns the slice of `array`.
	     * @example
	     *
	     * _.rest([1, 2, 3]);
	     * // => [2, 3]
	     */
	    function rest(array) {
	      return drop(array, 1);
	    }

	    /**
	     * Creates a slice of `array` from `start` up to, but not including, `end`.
	     *
	     * **Note:** This function is used instead of `Array#slice` to support node
	     * lists in IE < 9 and to ensure dense arrays are returned.
	     *
	     * @static
	     * @memberOf _
	     * @category Array
	     * @param {Array} array The array to slice.
	     * @param {number} [start=0] The start position.
	     * @param {number} [end=array.length] The end position.
	     * @returns {Array} Returns the slice of `array`.
	     */
	    function slice(array, start, end) {
	      var length = array ? array.length : 0;
	      if (!length) {
	        return [];
	      }
	      if (end && typeof end != 'number' && isIterateeCall(array, start, end)) {
	        start = 0;
	        end = length;
	      }
	      return baseSlice(array, start, end);
	    }

	    /**
	     * Uses a binary search to determine the lowest index at which `value` should
	     * be inserted into `array` in order to maintain its sort order. If an iteratee
	     * function is provided it is invoked for `value` and each element of `array`
	     * to compute their sort ranking. The iteratee is bound to `thisArg` and
	     * invoked with one argument; (value).
	     *
	     * If a property name is provided for `predicate` the created "_.property"
	     * style callback returns the property value of the given element.
	     *
	     * If an object is provided for `predicate` the created "_.matches" style
	     * callback returns `true` for elements that have the properties of the given
	     * object, else `false`.
	     *
	     * @static
	     * @memberOf _
	     * @category Array
	     * @param {Array} array The sorted array to inspect.
	     * @param {*} value The value to evaluate.
	     * @param {Function|Object|string} [iteratee=_.identity] The function invoked
	     *  per iteration. If a property name or object is provided it is used to
	     *  create a "_.property" or "_.matches" style callback respectively.
	     * @param {*} [thisArg] The `this` binding of `iteratee`.
	     * @returns {number} Returns the index at which `value` should be inserted
	     *  into `array`.
	     * @example
	     *
	     * _.sortedIndex([30, 50], 40);
	     * // => 1
	     *
	     * _.sortedIndex([4, 4, 5, 5, 6, 6], 5);
	     * // => 2
	     *
	     * var dict = { 'data': { 'thirty': 30, 'forty': 40, 'fifty': 50 } };
	     *
	     * // using an iteratee function
	     * _.sortedIndex(['thirty', 'fifty'], 'forty', function(word) {
	     *   return this.data[word];
	     * }, dict);
	     * // => 1
	     *
	     * // using the "_.property" callback shorthand
	     * _.sortedIndex([{ 'x': 30 }, { 'x': 50 }], { 'x': 40 }, 'x');
	     * // => 1
	     */
	    function sortedIndex(array, value, iteratee, thisArg) {
	      var func = getCallback(iteratee);
	      return (func === baseCallback && iteratee == null)
	        ? binaryIndex(array, value)
	        : binaryIndexBy(array, value, func(iteratee, thisArg, 1));
	    }

	    /**
	     * This method is like `_.sortedIndex` except that it returns the highest
	     * index at which `value` should be inserted into `array` in order to
	     * maintain its sort order.
	     *
	     * @static
	     * @memberOf _
	     * @category Array
	     * @param {Array} array The sorted array to inspect.
	     * @param {*} value The value to evaluate.
	     * @param {Function|Object|string} [iteratee=_.identity] The function invoked
	     *  per iteration. If a property name or object is provided it is used to
	     *  create a "_.property" or "_.matches" style callback respectively.
	     * @param {*} [thisArg] The `this` binding of `iteratee`.
	     * @returns {number} Returns the index at which `value` should be inserted
	     *  into `array`.
	     * @example
	     *
	     * _.sortedLastIndex([4, 4, 5, 5, 6, 6], 5);
	     * // => 4
	     */
	    function sortedLastIndex(array, value, iteratee, thisArg) {
	      var func = getCallback(iteratee);
	      return (func === baseCallback && iteratee == null)
	        ? binaryIndex(array, value, true)
	        : binaryIndexBy(array, value, func(iteratee, thisArg, 1), true);
	    }

	    /**
	     * Creates a slice of `array` with `n` elements taken from the beginning.
	     *
	     * @static
	     * @memberOf _
	     * @type Function
	     * @category Array
	     * @param {Array} array The array to query.
	     * @param {number} [n=1] The number of elements to take.
	     * @param- {Object} [guard] Enables use as a callback for functions like `_.map`.
	     * @returns {Array} Returns the slice of `array`.
	     * @example
	     *
	     * _.take([1, 2, 3]);
	     * // => [1]
	     *
	     * _.take([1, 2, 3], 2);
	     * // => [1, 2]
	     *
	     * _.take([1, 2, 3], 5);
	     * // => [1, 2, 3]
	     *
	     * _.take([1, 2, 3], 0);
	     * // => []
	     */
	    function take(array, n, guard) {
	      var length = array ? array.length : 0;
	      if (!length) {
	        return [];
	      }
	      if (guard ? isIterateeCall(array, n, guard) : n == null) {
	        n = 1;
	      }
	      return baseSlice(array, 0, n < 0 ? 0 : n);
	    }

	    /**
	     * Creates a slice of `array` with `n` elements taken from the end.
	     *
	     * @static
	     * @memberOf _
	     * @type Function
	     * @category Array
	     * @param {Array} array The array to query.
	     * @param {number} [n=1] The number of elements to take.
	     * @param- {Object} [guard] Enables use as a callback for functions like `_.map`.
	     * @returns {Array} Returns the slice of `array`.
	     * @example
	     *
	     * _.takeRight([1, 2, 3]);
	     * // => [3]
	     *
	     * _.takeRight([1, 2, 3], 2);
	     * // => [2, 3]
	     *
	     * _.takeRight([1, 2, 3], 5);
	     * // => [1, 2, 3]
	     *
	     * _.takeRight([1, 2, 3], 0);
	     * // => []
	     */
	    function takeRight(array, n, guard) {
	      var length = array ? array.length : 0;
	      if (!length) {
	        return [];
	      }
	      if (guard ? isIterateeCall(array, n, guard) : n == null) {
	        n = 1;
	      }
	      n = length - (+n || 0);
	      return baseSlice(array, n < 0 ? 0 : n);
	    }

	    /**
	     * Creates a slice of `array` with elements taken from the end. Elements are
	     * taken until `predicate` returns falsey. The predicate is bound to `thisArg`
	     * and invoked with three arguments; (value, index, array).
	     *
	     * If a property name is provided for `predicate` the created "_.property"
	     * style callback returns the property value of the given element.
	     *
	     * If an object is provided for `predicate` the created "_.matches" style
	     * callback returns `true` for elements that have the properties of the given
	     * object, else `false`.
	     *
	     * @static
	     * @memberOf _
	     * @type Function
	     * @category Array
	     * @param {Array} array The array to query.
	     * @param {Function|Object|string} [predicate=_.identity] The function invoked
	     *  per element.
	     * @param {*} [thisArg] The `this` binding of `predicate`.
	     * @returns {Array} Returns the slice of `array`.
	     * @example
	     *
	     * _.takeRightWhile([1, 2, 3], function(n) { return n > 1; });
	     * // => [2, 3]
	     *
	     * var users = [
	     *   { 'user': 'barney',  'status': 'busy', 'active': false },
	     *   { 'user': 'fred',    'status': 'busy', 'active': true },
	     *   { 'user': 'pebbles', 'status': 'away', 'active': true }
	     * ];
	     *
	     * // using the "_.property" callback shorthand
	     * _.pluck(_.takeRightWhile(users, 'active'), 'user');
	     * // => ['fred', 'pebbles']
	     *
	     * // using the "_.matches" callback shorthand
	     * _.pluck(_.takeRightWhile(users, { 'status': 'away' }), 'user');
	     * // => ['pebbles']
	     */
	    function takeRightWhile(array, predicate, thisArg) {
	      var length = array ? array.length : 0;
	      if (!length) {
	        return [];
	      }
	      predicate = getCallback(predicate, thisArg, 3);
	      while (length-- && predicate(array[length], length, array)) {}
	      return baseSlice(array, length + 1);
	    }

	    /**
	     * Creates a slice of `array` with elements taken from the beginning. Elements
	     * are taken until `predicate` returns falsey. The predicate is bound to
	     * `thisArg` and invoked with three arguments; (value, index, array).
	     *
	     * If a property name is provided for `predicate` the created "_.property"
	     * style callback returns the property value of the given element.
	     *
	     * If an object is provided for `predicate` the created "_.matches" style
	     * callback returns `true` for elements that have the properties of the given
	     * object, else `false`.
	     *
	     * @static
	     * @memberOf _
	     * @type Function
	     * @category Array
	     * @param {Array} array The array to query.
	     * @param {Function|Object|string} [predicate=_.identity] The function invoked
	     *  per element.
	     * @param {*} [thisArg] The `this` binding of `predicate`.
	     * @returns {Array} Returns the slice of `array`.
	     * @example
	     *
	     * _.takeWhile([1, 2, 3], function(n) { return n < 3; });
	     * // => [1, 2]
	     *
	     * var users = [
	     *   { 'user': 'barney',  'status': 'busy', 'active': true },
	     *   { 'user': 'fred',    'status': 'busy', 'active': false },
	     *   { 'user': 'pebbles', 'status': 'away', 'active': true }
	     * ];
	     *
	     * // using the "_.property" callback shorthand
	     * _.pluck(_.takeWhile(users, 'active'), 'user');
	     * // => ['barney']
	     *
	     * // using the "_.matches" callback shorthand
	     * _.pluck(_.takeWhile(users, { 'status': 'busy' }), 'user');
	     * // => ['barney', 'fred']
	     */
	    function takeWhile(array, predicate, thisArg) {
	      var length = array ? array.length : 0;
	      if (!length) {
	        return [];
	      }
	      var index = -1;
	      predicate = getCallback(predicate, thisArg, 3);
	      while (++index < length && predicate(array[index], index, array)) {}
	      return baseSlice(array, 0, index);
	    }

	    /**
	     * Creates an array of unique values, in order, of the provided arrays using
	     * `SameValueZero` for equality comparisons.
	     *
	     * **Note:** `SameValueZero` comparisons are like strict equality comparisons,
	     * e.g. `===`, except that `NaN` matches `NaN`. See the
	     * [ES spec](https://people.mozilla.org/~jorendorff/es6-draft.html#sec-samevaluezero)
	     * for more details.
	     *
	     * @static
	     * @memberOf _
	     * @category Array
	     * @param {...Array} [arrays] The arrays to inspect.
	     * @returns {Array} Returns the new array of combined values.
	     * @example
	     *
	     * _.union([1, 2, 3], [5, 2, 1, 4], [2, 1]);
	     * // => [1, 2, 3, 5, 4]
	     */
	    function union() {
	      return baseUniq(baseFlatten(arguments, false, true));
	    }

	    /**
	     * Creates a duplicate-value-free version of an array using `SameValueZero`
	     * for equality comparisons. Providing `true` for `isSorted` performs a faster
	     * search algorithm for sorted arrays. If an iteratee function is provided it
	     * is invoked for each value in the array to generate the criterion by which
	     * uniqueness is computed. The `iteratee` is bound to `thisArg` and invoked
	     * with three arguments; (value, index, array).
	     *
	     * If a property name is provided for `predicate` the created "_.property"
	     * style callback returns the property value of the given element.
	     *
	     * If an object is provided for `predicate` the created "_.matches" style
	     * callback returns `true` for elements that have the properties of the given
	     * object, else `false`.
	     *
	     * **Note:** `SameValueZero` comparisons are like strict equality comparisons,
	     * e.g. `===`, except that `NaN` matches `NaN`. See the
	     * [ES spec](https://people.mozilla.org/~jorendorff/es6-draft.html#sec-samevaluezero)
	     * for more details.
	     *
	     * @static
	     * @memberOf _
	     * @alias unique
	     * @category Array
	     * @param {Array} array The array to inspect.
	     * @param {boolean} [isSorted] Specify the array is sorted.
	     * @param {Function|Object|string} [iteratee] The function invoked per iteration.
	     *  If a property name or object is provided it is used to create a "_.property"
	     *  or "_.matches" style callback respectively.
	     * @param {*} [thisArg] The `this` binding of `iteratee`.
	     * @returns {Array} Returns the new duplicate-value-free array.
	     * @example
	     *
	     * _.uniq([1, 2, 1]);
	     * // => [1, 2]
	     *
	     * // using `isSorted`
	     * _.uniq([1, 1, 2], true);
	     * // => [1, 2]
	     *
	     * // using an iteratee function
	     * _.uniq([1, 2.5, 1.5, 2], function(n) { return this.floor(n); }, Math);
	     * // => [1, 2.5]
	     *
	     * // using the "_.property" callback shorthand
	     * _.uniq([{ 'x': 1 }, { 'x': 2 }, { 'x': 1 }], 'x');
	     * // => [{ 'x': 1 }, { 'x': 2 }]
	     */
	    function uniq(array, isSorted, iteratee, thisArg) {
	      var length = array ? array.length : 0;
	      if (!length) {
	        return [];
	      }
	      // Juggle arguments.
	      if (typeof isSorted != 'boolean' && isSorted != null) {
	        thisArg = iteratee;
	        iteratee = isIterateeCall(array, isSorted, thisArg) ? null : isSorted;
	        isSorted = false;
	      }
	      var func = getCallback();
	      if (!(func === baseCallback && iteratee == null)) {
	        iteratee = func(iteratee, thisArg, 3);
	      }
	      return (isSorted && getIndexOf() == baseIndexOf)
	        ? sortedUniq(array, iteratee)
	        : baseUniq(array, iteratee);
	    }

	    /**
	     * This method is like `_.zip` except that it accepts an array of grouped
	     * elements and creates an array regrouping the elements to their pre-`_.zip`
	     * configuration.
	     *
	     * @static
	     * @memberOf _
	     * @category Array
	     * @param {Array} array The array of grouped elements to process.
	     * @returns {Array} Returns the new array of regrouped elements.
	     * @example
	     *
	     * var zipped = _.zip(['fred', 'barney'], [30, 40], [true, false]);
	     * // => [['fred', 30, true], ['barney', 40, false]]
	     *
	     * _.unzip(zipped);
	     * // => [['fred', 'barney'], [30, 40], [true, false]]
	     */
	    function unzip(array) {
	      var index = -1,
	          length = (array && array.length && arrayMax(arrayMap(array, getLength))) >>> 0,
	          result = Array(length);

	      while (++index < length) {
	        result[index] = arrayMap(array, baseProperty(index));
	      }
	      return result;
	    }

	    /**
	     * Creates an array excluding all provided values using `SameValueZero` for
	     * equality comparisons.
	     *
	     * **Note:** `SameValueZero` comparisons are like strict equality comparisons,
	     * e.g. `===`, except that `NaN` matches `NaN`. See the
	     * [ES spec](https://people.mozilla.org/~jorendorff/es6-draft.html#sec-samevaluezero)
	     * for more details.
	     *
	     * @static
	     * @memberOf _
	     * @category Array
	     * @param {Array} array The array to filter.
	     * @param {...*} [values] The values to exclude.
	     * @returns {Array} Returns the new array of filtered values.
	     * @example
	     *
	     * _.without([1, 2, 1, 0, 3, 1, 4], 0, 1);
	     * // => [2, 3, 4]
	     */
	    function without(array) {
	      return baseDifference(array, baseSlice(arguments, 1));
	    }

	    /**
	     * Creates an array that is the symmetric difference of the provided arrays.
	     * See [Wikipedia](https://en.wikipedia.org/wiki/Symmetric_difference) for
	     * more details.
	     *
	     * @static
	     * @memberOf _
	     * @category Array
	     * @param {...Array} [arrays] The arrays to inspect.
	     * @returns {Array} Returns the new array of values.
	     * @example
	     *
	     * _.xor([1, 2, 3], [5, 2, 1, 4]);
	     * // => [3, 5, 4]
	     *
	     * _.xor([1, 2, 5], [2, 3, 5], [3, 4, 5]);
	     * // => [1, 4, 5]
	     */
	    function xor() {
	      var index = -1,
	          length = arguments.length;

	      while (++index < length) {
	        var array = arguments[index];
	        if (isArray(array) || isArguments(array)) {
	          var result = result
	            ? baseDifference(result, array).concat(baseDifference(array, result))
	            : array;
	        }
	      }
	      return result ? baseUniq(result) : [];
	    }

	    /**
	     * Creates an array of grouped elements, the first of which contains the first
	     * elements of the given arrays, the second of which contains the second elements
	     * of the given arrays, and so on.
	     *
	     * @static
	     * @memberOf _
	     * @category Array
	     * @param {...Array} [arrays] The arrays to process.
	     * @returns {Array} Returns the new array of grouped elements.
	     * @example
	     *
	     * _.zip(['fred', 'barney'], [30, 40], [true, false]);
	     * // => [['fred', 30, true], ['barney', 40, false]]
	     */
	    function zip() {
	      var length = arguments.length,
	          array = Array(length);

	      while (length--) {
	        array[length] = arguments[length];
	      }
	      return unzip(array);
	    }

	    /**
	     * Creates an object composed from arrays of property names and values. Provide
	     * either a single two dimensional array, e.g. `[[key1, value1], [key2, value2]]`
	     * or two arrays, one of property names and one of corresponding values.
	     *
	     * @static
	     * @memberOf _
	     * @alias object
	     * @category Array
	     * @param {Array} props The property names.
	     * @param {Array} [values=[]] The property values.
	     * @returns {Object} Returns the new object.
	     * @example
	     *
	     * _.zipObject(['fred', 'barney'], [30, 40]);
	     * // => { 'fred': 30, 'barney': 40 }
	     */
	    function zipObject(props, values) {
	      var index = -1,
	          length = props ? props.length : 0,
	          result = {};

	      if (length && !values && !isArray(props[0])) {
	        values = [];
	      }
	      while (++index < length) {
	        var key = props[index];
	        if (values) {
	          result[key] = values[index];
	        } else if (key) {
	          result[key[0]] = key[1];
	        }
	      }
	      return result;
	    }

	    /*------------------------------------------------------------------------*/

	    /**
	     * Creates a `lodash` object that wraps `value` with explicit method
	     * chaining enabled.
	     *
	     * @static
	     * @memberOf _
	     * @category Chain
	     * @param {*} value The value to wrap.
	     * @returns {Object} Returns the new `lodash` object.
	     * @example
	     *
	     * var users = [
	     *   { 'user': 'barney',  'age': 36 },
	     *   { 'user': 'fred',    'age': 40 },
	     *   { 'user': 'pebbles', 'age': 1 }
	     * ];
	     *
	     * var youngest = _.chain(users)
	     *   .sortBy('age')
	     *   .map(function(chr) { return chr.user + ' is ' + chr.age; })
	     *   .first()
	     *   .value();
	     * // => 'pebbles is 1'
	     */
	    function chain(value) {
	      var result = lodash(value);
	      result.__chain__ = true;
	      return result;
	    }

	    /**
	     * This method invokes `interceptor` and returns `value`. The interceptor is
	     * bound to `thisArg` and invoked with one argument; (value). The purpose of
	     * this method is to "tap into" a method chain in order to perform operations
	     * on intermediate results within the chain.
	     *
	     * @static
	     * @memberOf _
	     * @category Chain
	     * @param {*} value The value to provide to `interceptor`.
	     * @param {Function} interceptor The function to invoke.
	     * @param {*} [thisArg] The `this` binding of `interceptor`.
	     * @returns {*} Returns `value`.
	     * @example
	     *
	     * _([1, 2, 3])
	     *  .tap(function(array) { array.pop(); })
	     *  .reverse()
	     *  .value();
	     * // => [2, 1]
	     */
	    function tap(value, interceptor, thisArg) {
	      interceptor.call(thisArg, value);
	      return value;
	    }

	    /**
	     * This method is like `_.tap` except that it returns the result of `interceptor`.
	     *
	     * @static
	     * @memberOf _
	     * @category Chain
	     * @param {*} value The value to provide to `interceptor`.
	     * @param {Function} interceptor The function to invoke.
	     * @param {*} [thisArg] The `this` binding of `interceptor`.
	     * @returns {*} Returns the result of `interceptor`.
	     * @example
	     *
	     * _([1, 2, 3])
	     *  .last()
	     *  .thru(function(value) { return [value]; })
	     *  .value();
	     * // => [3]
	     */
	    function thru(value, interceptor, thisArg) {
	      return interceptor.call(thisArg, value);
	    }

	    /**
	     * Enables explicit method chaining on the wrapper object.
	     *
	     * @name chain
	     * @memberOf _
	     * @category Chain
	     * @returns {*} Returns the `lodash` object.
	     * @example
	     *
	     * var users = [
	     *   { 'user': 'barney', 'age': 36 },
	     *   { 'user': 'fred',   'age': 40 }
	     * ];
	     *
	     * // without explicit chaining
	     * _(users).first();
	     * // => { 'user': 'barney', 'age': 36 }
	     *
	     * // with explicit chaining
	     * _(users).chain()
	     *   .first()
	     *   .pick('user')
	     *   .value();
	     * // => { 'user': 'barney' }
	     */
	    function wrapperChain() {
	      return chain(this);
	    }

	    /**
	     * Reverses the wrapped array so the first element becomes the last, the
	     * second element becomes the second to last, and so on.
	     *
	     * **Note:** This method mutates the wrapped array.
	     *
	     * @name reverse
	     * @memberOf _
	     * @category Chain
	     * @returns {Object} Returns the new reversed `lodash` object.
	     * @example
	     *
	     * var array = [1, 2, 3];
	     *
	     * _(array).reverse().value()
	     * // => [3, 2, 1]
	     *
	     * console.log(array);
	     * // => [3, 2, 1]
	     */
	    function wrapperReverse() {
	      var value = this.__wrapped__;
	      if (value instanceof LazyWrapper) {
	        if (this.__actions__.length) {
	          value = new LazyWrapper(this);
	        }
	        return new LodashWrapper(value.reverse());
	      }
	      return this.thru(function(value) {
	        return value.reverse();
	      });
	    }

	    /**
	     * Produces the result of coercing the unwrapped value to a string.
	     *
	     * @name toString
	     * @memberOf _
	     * @category Chain
	     * @returns {string} Returns the coerced string value.
	     * @example
	     *
	     * _([1, 2, 3]).toString();
	     * // => '1,2,3'
	     */
	    function wrapperToString() {
	      return (this.value() + '');
	    }

	    /**
	     * Executes the chained sequence to extract the unwrapped value.
	     *
	     * @name value
	     * @memberOf _
	     * @alias toJSON, valueOf
	     * @category Chain
	     * @returns {*} Returns the resolved unwrapped value.
	     * @example
	     *
	     * _([1, 2, 3]).value();
	     * // => [1, 2, 3]
	     */
	    function wrapperValue() {
	      return baseWrapperValue(this.__wrapped__, this.__actions__);
	    }

	    /*------------------------------------------------------------------------*/

	    /**
	     * Creates an array of elements corresponding to the given keys, or indexes,
	     * of `collection`. Keys may be specified as individual arguments or as arrays
	     * of keys.
	     *
	     * @static
	     * @memberOf _
	     * @category Collection
	     * @param {Array|Object|string} collection The collection to iterate over.
	     * @param {...(number|number[]|string|string[])} [props] The property names
	     *  or indexes of elements to pick, specified individually or in arrays.
	     * @returns {Array} Returns the new array of picked elements.
	     * @example
	     *
	     * _.at(['a', 'b', 'c', 'd', 'e'], [0, 2, 4]);
	     * // => ['a', 'c', 'e']
	     *
	     * _.at(['fred', 'barney', 'pebbles'], 0, 2);
	     * // => ['fred', 'pebbles']
	     */
	    function at(collection) {
	      var length = collection ? collection.length : 0;
	      if (isLength(length)) {
	        collection = toIterable(collection);
	      }
	      return baseAt(collection, baseFlatten(arguments, false, false, 1));
	    }

	    /**
	     * Checks if `value` is in `collection` using `SameValueZero` for equality
	     * comparisons. If `fromIndex` is negative, it is used as the offset from
	     * the end of `collection`.
	     *
	     * **Note:** `SameValueZero` comparisons are like strict equality comparisons,
	     * e.g. `===`, except that `NaN` matches `NaN`. See the
	     * [ES spec](https://people.mozilla.org/~jorendorff/es6-draft.html#sec-samevaluezero)
	     * for more details.
	     *
	     * @static
	     * @memberOf _
	     * @alias contains, include
	     * @category Collection
	     * @param {Array|Object|string} collection The collection to search.
	     * @param {*} target The value to search for.
	     * @param {number} [fromIndex=0] The index to search from.
	     * @returns {boolean} Returns `true` if a matching element is found, else `false`.
	     * @example
	     *
	     * _.includes([1, 2, 3], 1);
	     * // => true
	     *
	     * _.includes([1, 2, 3], 1, 2);
	     * // => false
	     *
	     * _.includes({ 'user': 'fred', 'age': 40 }, 'fred');
	     * // => true
	     *
	     * _.includes('pebbles', 'eb');
	     * // => true
	     */
	    function includes(collection, target, fromIndex) {
	      var length = collection ? collection.length : 0;
	      if (!isLength(length)) {
	        collection = values(collection);
	        length = collection.length;
	      }
	      if (!length) {
	        return false;
	      }
	      if (typeof fromIndex == 'number') {
	        fromIndex = fromIndex < 0 ? nativeMax(length + fromIndex, 0) : (fromIndex || 0);
	      } else {
	        fromIndex = 0;
	      }
	      return (typeof collection == 'string' || !isArray(collection) && isString(collection))
	        ? (fromIndex < length && collection.indexOf(target, fromIndex) > -1)
	        : (getIndexOf(collection, target, fromIndex) > -1);
	    }

	    /**
	     * Creates an object composed of keys generated from the results of running
	     * each element of `collection` through `iteratee`. The corresponding value
	     * of each key is the number of times the key was returned by `iteratee`.
	     * The `iteratee` is bound to `thisArg` and invoked with three arguments;
	     * (value, index|key, collection).
	     *
	     * If a property name is provided for `predicate` the created "_.property"
	     * style callback returns the property value of the given element.
	     *
	     * If an object is provided for `predicate` the created "_.matches" style
	     * callback returns `true` for elements that have the properties of the given
	     * object, else `false`.
	     *
	     * @static
	     * @memberOf _
	     * @category Collection
	     * @param {Array|Object|string} collection The collection to iterate over.
	     * @param {Function|Object|string} [iteratee=_.identity] The function invoked
	     *  per iteration. If a property name or object is provided it is used to
	     *  create a "_.property" or "_.matches" style callback respectively.
	     * @param {*} [thisArg] The `this` binding of `iteratee`.
	     * @returns {Object} Returns the composed aggregate object.
	     * @example
	     *
	     * _.countBy([4.3, 6.1, 6.4], function(n) { return Math.floor(n); });
	     * // => { '4': 1, '6': 2 }
	     *
	     * _.countBy([4.3, 6.1, 6.4], function(n) { return this.floor(n); }, Math);
	     * // => { '4': 1, '6': 2 }
	     *
	     * _.countBy(['one', 'two', 'three'], 'length');
	     * // => { '3': 2, '5': 1 }
	     */
	    var countBy = createAggregator(function(result, value, key) {
	      hasOwnProperty.call(result, key) ? ++result[key] : (result[key] = 1);
	    });

	    /**
	     * Checks if `predicate` returns truthy for **all** elements of `collection`.
	     * The predicate is bound to `thisArg` and invoked with three arguments;
	     * (value, index|key, collection).
	     *
	     * If a property name is provided for `predicate` the created "_.property"
	     * style callback returns the property value of the given element.
	     *
	     * If an object is provided for `predicate` the created "_.matches" style
	     * callback returns `true` for elements that have the properties of the given
	     * object, else `false`.
	     *
	     * @static
	     * @memberOf _
	     * @alias all
	     * @category Collection
	     * @param {Array|Object|string} collection The collection to iterate over.
	     * @param {Function|Object|string} [predicate=_.identity] The function invoked
	     *  per iteration. If a property name or object is provided it is used to
	     *  create a "_.property" or "_.matches" style callback respectively.
	     * @param {*} [thisArg] The `this` binding of `predicate`.
	     * @returns {boolean} Returns `true` if all elements pass the predicate check,
	     *  else `false`.
	     * @example
	     *
	     * _.every([true, 1, null, 'yes']);
	     * // => false
	     *
	     * var users = [
	     *   { 'user': 'barney', 'age': 36 },
	     *   { 'user': 'fred',   'age': 40 }
	     * ];
	     *
	     * // using the "_.property" callback shorthand
	     * _.every(users, 'age');
	     * // => true
	     *
	     * // using the "_.matches" callback shorthand
	     * _.every(users, { 'age': 36 });
	     * // => false
	     */
	    function every(collection, predicate, thisArg) {
	      var func = isArray(collection) ? arrayEvery : baseEvery;
	      if (typeof predicate != 'function' || typeof thisArg != 'undefined') {
	        predicate = getCallback(predicate, thisArg, 3);
	      }
	      return func(collection, predicate);
	    }

	    /**
	     * Iterates over elements of `collection`, returning an array of all elements
	     * `predicate` returns truthy for. The predicate is bound to `thisArg` and
	     * invoked with three arguments; (value, index|key, collection).
	     *
	     * If a property name is provided for `predicate` the created "_.property"
	     * style callback returns the property value of the given element.
	     *
	     * If an object is provided for `predicate` the created "_.matches" style
	     * callback returns `true` for elements that have the properties of the given
	     * object, else `false`.
	     *
	     * @static
	     * @memberOf _
	     * @alias select
	     * @category Collection
	     * @param {Array|Object|string} collection The collection to iterate over.
	     * @param {Function|Object|string} [predicate=_.identity] The function invoked
	     *  per iteration. If a property name or object is provided it is used to
	     *  create a "_.property" or "_.matches" style callback respectively.
	     * @param {*} [thisArg] The `this` binding of `predicate`.
	     * @returns {Array} Returns the new filtered array.
	     * @example
	     *
	     * var evens = _.filter([1, 2, 3, 4], function(n) { return n % 2 == 0; });
	     * // => [2, 4]
	     *
	     * var users = [
	     *   { 'user': 'barney', 'age': 36, 'active': false },
	     *   { 'user': 'fred',   'age': 40, 'active': true }
	     * ];
	     *
	     * // using the "_.property" callback shorthand
	     * _.pluck(_.filter(users, 'active'), 'user');
	     * // => ['fred']
	     *
	     * // using the "_.matches" callback shorthand
	     * _.pluck(_.filter(users, { 'age': 36 }), 'user');
	     * // => ['barney']
	     */
	    function filter(collection, predicate, thisArg) {
	      var func = isArray(collection) ? arrayFilter : baseFilter;
	      predicate = getCallback(predicate, thisArg, 3);
	      return func(collection, predicate);
	    }

	    /**
	     * Iterates over elements of `collection`, returning the first element
	     * `predicate` returns truthy for. The predicate is bound to `thisArg` and
	     * invoked with three arguments; (value, index|key, collection).
	     *
	     * If a property name is provided for `predicate` the created "_.property"
	     * style callback returns the property value of the given element.
	     *
	     * If an object is provided for `predicate` the created "_.matches" style
	     * callback returns `true` for elements that have the properties of the given
	     * object, else `false`.
	     *
	     * @static
	     * @memberOf _
	     * @alias detect
	     * @category Collection
	     * @param {Array|Object|string} collection The collection to search.
	     * @param {Function|Object|string} [predicate=_.identity] The function invoked
	     *  per iteration. If a property name or object is provided it is used to
	     *  create a "_.property" or "_.matches" style callback respectively.
	     * @param {*} [thisArg] The `this` binding of `predicate`.
	     * @returns {*} Returns the matched element, else `undefined`.
	     * @example
	     *
	     * var users = [
	     *   { 'user': 'barney',  'age': 36, 'active': false },
	     *   { 'user': 'fred',    'age': 40, 'active': true },
	     *   { 'user': 'pebbles', 'age': 1,  'active': false }
	     * ];
	     *
	     * _.result(_.find(users, function(chr) { return chr.age < 40; }), 'user');
	     * // => 'barney'
	     *
	     * // using the "_.matches" callback shorthand
	     * _.result(_.find(users, { 'age': 1 }), 'user');
	     * // => 'pebbles'
	     *
	     * // using the "_.property" callback shorthand
	     * _.result(_.find(users, 'active'), 'user');
	     * // => 'fred'
	     */
	    function find(collection, predicate, thisArg) {
	      if (isArray(collection)) {
	        var index = findIndex(collection, predicate, thisArg);
	        return index > -1 ? collection[index] : undefined;
	      }
	      predicate = getCallback(predicate, thisArg, 3);
	      return baseFind(collection, predicate, baseEach);
	    }

	    /**
	     * This method is like `_.find` except that it iterates over elements of
	     * `collection` from right to left.
	     *
	     * @static
	     * @memberOf _
	     * @category Collection
	     * @param {Array|Object|string} collection The collection to search.
	     * @param {Function|Object|string} [predicate=_.identity] The function invoked
	     *  per iteration. If a property name or object is provided it is used to
	     *  create a "_.property" or "_.matches" style callback respectively.
	     * @param {*} [thisArg] The `this` binding of `predicate`.
	     * @returns {*} Returns the matched element, else `undefined`.
	     * @example
	     *
	     * _.findLast([1, 2, 3, 4], function(n) { return n % 2 == 1; });
	     * // => 3
	     */
	    function findLast(collection, predicate, thisArg) {
	      predicate = getCallback(predicate, thisArg, 3);
	      return baseFind(collection, predicate, baseEachRight);
	    }

	    /**
	     * Performs a deep comparison between each element in `collection` and the
	     * source object, returning the first element that has equivalent property
	     * values.
	     *
	     * @static
	     * @memberOf _
	     * @category Collection
	     * @param {Array|Object|string} collection The collection to search.
	     * @param {Object} source The object of property values to match.
	     * @returns {*} Returns the matched element, else `undefined`.
	     * @example
	     *
	     * var users = [
	     *   { 'user': 'barney', 'age': 36, 'status': 'busy' },
	     *   { 'user': 'fred',   'age': 40, 'status': 'busy' }
	     * ];
	     *
	     * _.result(_.findWhere(users, { 'status': 'busy' }), 'user');
	     * // => 'barney'
	     *
	     * _.result(_.findWhere(users, { 'age': 40 }), 'user');
	     * // => 'fred'
	     */
	    function findWhere(collection, source) {
	      return find(collection, baseMatches(source));
	    }

	    /**
	     * Iterates over elements of `collection` invoking `iteratee` for each element.
	     * The `iteratee` is bound to `thisArg` and invoked with three arguments;
	     * (value, index|key, collection). Iterator functions may exit iteration early
	     * by explicitly returning `false`.
	     *
	     * **Note:** As with other "Collections" methods, objects with a `length` property
	     * are iterated like arrays. To avoid this behavior `_.forIn` or `_.forOwn`
	     * may be used for object iteration.
	     *
	     * @static
	     * @memberOf _
	     * @alias each
	     * @category Collection
	     * @param {Array|Object|string} collection The collection to iterate over.
	     * @param {Function} [iteratee=_.identity] The function invoked per iteration.
	     * @param {*} [thisArg] The `this` binding of `iteratee`.
	     * @returns {Array|Object|string} Returns `collection`.
	     * @example
	     *
	     * _([1, 2, 3]).forEach(function(n) { console.log(n); }).value();
	     * // => logs each value from left to right and returns the array
	     *
	     * _.forEach({ 'one': 1, 'two': 2, 'three': 3 }, function(n, key) { console.log(n, key); });
	     * // => logs each value-key pair and returns the object (iteration order is not guaranteed)
	     */
	    function forEach(collection, iteratee, thisArg) {
	      return (typeof iteratee == 'function' && typeof thisArg == 'undefined' && isArray(collection))
	        ? arrayEach(collection, iteratee)
	        : baseEach(collection, bindCallback(iteratee, thisArg, 3));
	    }

	    /**
	     * This method is like `_.forEach` except that it iterates over elements of
	     * `collection` from right to left.
	     *
	     * @static
	     * @memberOf _
	     * @alias eachRight
	     * @category Collection
	     * @param {Array|Object|string} collection The collection to iterate over.
	     * @param {Function} [iteratee=_.identity] The function invoked per iteration.
	     * @param {*} [thisArg] The `this` binding of `iteratee`.
	     * @returns {Array|Object|string} Returns `collection`.
	     * @example
	     *
	     * _([1, 2, 3]).forEachRight(function(n) { console.log(n); }).join(',');
	     * // => logs each value from right to left and returns the array
	     */
	    function forEachRight(collection, iteratee, thisArg) {
	      return (typeof iteratee == 'function' && typeof thisArg == 'undefined' && isArray(collection))
	        ? arrayEachRight(collection, iteratee)
	        : baseEachRight(collection, bindCallback(iteratee, thisArg, 3));
	    }

	    /**
	     * Creates an object composed of keys generated from the results of running
	     * each element of `collection` through `iteratee`. The corresponding value
	     * of each key is an array of the elements responsible for generating the key.
	     * The `iteratee` is bound to `thisArg` and invoked with three arguments;
	     * (value, index|key, collection).
	     *
	     * If a property name is provided for `predicate` the created "_.property"
	     * style callback returns the property value of the given element.
	     *
	     * If an object is provided for `predicate` the created "_.matches" style
	     * callback returns `true` for elements that have the properties of the given
	     * object, else `false`.
	     *
	     * @static
	     * @memberOf _
	     * @category Collection
	     * @param {Array|Object|string} collection The collection to iterate over.
	     * @param {Function|Object|string} [iteratee=_.identity] The function invoked
	     *  per iteration. If a property name or object is provided it is used to
	     *  create a "_.property" or "_.matches" style callback respectively.
	     * @param {*} [thisArg] The `this` binding of `iteratee`.
	     * @returns {Object} Returns the composed aggregate object.
	     * @example
	     *
	     * _.groupBy([4.2, 6.1, 6.4], function(n) { return Math.floor(n); });
	     * // => { '4': [4.2], '6': [6.1, 6.4] }
	     *
	     * _.groupBy([4.2, 6.1, 6.4], function(n) { return this.floor(n); }, Math);
	     * // => { '4': [4.2], '6': [6.1, 6.4] }
	     *
	     * // using the "_.property" callback shorthand
	     * _.groupBy(['one', 'two', 'three'], 'length');
	     * // => { '3': ['one', 'two'], '5': ['three'] }
	     */
	    var groupBy = createAggregator(function(result, value, key) {
	      if (hasOwnProperty.call(result, key)) {
	        result[key].push(value);
	      } else {
	        result[key] = [value];
	      }
	    });

	    /**
	     * Creates an object composed of keys generated from the results of running
	     * each element of `collection` through `iteratee`. The corresponding value
	     * of each key is the last element responsible for generating the key. The
	     * iteratee function is bound to `thisArg` and invoked with three arguments;
	     * (value, index|key, collection).
	     *
	     * If a property name is provided for `predicate` the created "_.property"
	     * style callback returns the property value of the given element.
	     *
	     * If an object is provided for `predicate` the created "_.matches" style
	     * callback returns `true` for elements that have the properties of the given
	     * object, else `false`.
	     *
	     * @static
	     * @memberOf _
	     * @category Collection
	     * @param {Array|Object|string} collection The collection to iterate over.
	     * @param {Function|Object|string} [iteratee=_.identity] The function invoked
	     *  per iteration. If a property name or object is provided it is used to
	     *  create a "_.property" or "_.matches" style callback respectively.
	     * @param {*} [thisArg] The `this` binding of `iteratee`.
	     * @returns {Object} Returns the composed aggregate object.
	     * @example
	     *
	     * var keyData = [
	     *   { 'dir': 'left', 'code': 97 },
	     *   { 'dir': 'right', 'code': 100 }
	     * ];
	     *
	     * _.indexBy(keyData, 'dir');
	     * // => { 'left': { 'dir': 'left', 'code': 97 }, 'right': { 'dir': 'right', 'code': 100 } }
	     *
	     * _.indexBy(keyData, function(object) { return String.fromCharCode(object.code); });
	     * // => { 'a': { 'dir': 'left', 'code': 97 }, 'd': { 'dir': 'right', 'code': 100 } }
	     *
	     * _.indexBy(keyData, function(object) { return this.fromCharCode(object.code); }, String);
	     * // => { 'a': { 'dir': 'left', 'code': 97 }, 'd': { 'dir': 'right', 'code': 100 } }
	     */
	    var indexBy = createAggregator(function(result, value, key) {
	      result[key] = value;
	    });

	    /**
	     * Invokes the method named by `methodName` on each element in `collection`,
	     * returning an array of the results of each invoked method. Any additional
	     * arguments are provided to each invoked method. If `methodName` is a function
	     * it is invoked for, and `this` bound to, each element in `collection`.
	     *
	     * @static
	     * @memberOf _
	     * @category Collection
	     * @param {Array|Object|string} collection The collection to iterate over.
	     * @param {Function|string} methodName The name of the method to invoke or
	     *  the function invoked per iteration.
	     * @param {...*} [args] The arguments to invoke the method with.
	     * @returns {Array} Returns the array of results.
	     * @example
	     *
	     * _.invoke([[5, 1, 7], [3, 2, 1]], 'sort');
	     * // => [[1, 5, 7], [1, 2, 3]]
	     *
	     * _.invoke([123, 456], String.prototype.split, '');
	     * // => [['1', '2', '3'], ['4', '5', '6']]
	     */
	    function invoke(collection, methodName) {
	      return baseInvoke(collection, methodName, baseSlice(arguments, 2));
	    }

	    /**
	     * Creates an array of values by running each element in `collection` through
	     * `iteratee`. The `iteratee` is bound to `thisArg` and invoked with three
	     * arguments; (value, index|key, collection).
	     *
	     * If a property name is provided for `predicate` the created "_.property"
	     * style callback returns the property value of the given element.
	     *
	     * If an object is provided for `predicate` the created "_.matches" style
	     * callback returns `true` for elements that have the properties of the given
	     * object, else `false`.
	     *
	     * @static
	     * @memberOf _
	     * @alias collect
	     * @category Collection
	     * @param {Array|Object|string} collection The collection to iterate over.
	     * @param {Function|Object|string} [iteratee=_.identity] The function invoked
	     *  per iteration. If a property name or object is provided it is used to
	     *  create a "_.property" or "_.matches" style callback respectively.
	     * @param {*} [thisArg] The `this` binding of `iteratee`.
	     * @returns {Array} Returns the new mapped array.
	     * @example
	     *
	     * _.map([1, 2, 3], function(n) { return n * 3; });
	     * // => [3, 6, 9]
	     *
	     * _.map({ 'one': 1, 'two': 2, 'three': 3 }, function(n) { return n * 3; });
	     * // => [3, 6, 9] (iteration order is not guaranteed)
	     *
	     * var users = [
	     *   { 'user': 'barney' },
	     *   { 'user': 'fred' }
	     * ];
	     *
	     * // using the "_.property" callback shorthand
	     * _.map(users, 'user');
	     * // => ['barney', 'fred']
	     */
	    function map(collection, iteratee, thisArg) {
	      var func = isArray(collection) ? arrayMap : baseMap;
	      iteratee = getCallback(iteratee, thisArg, 3);
	      return func(collection, iteratee);
	    }

	    /**
	     * Gets the maximum value of `collection`. If `collection` is empty or falsey
	     * `-Infinity` is returned. If an iteratee function is provided it is invoked
	     * for each value in `collection` to generate the criterion by which the value
	     * is ranked. The `iteratee` is bound to `thisArg` and invoked with three
	     * arguments; (value, index, collection).
	     *
	     * If a property name is provided for `predicate` the created "_.property"
	     * style callback returns the property value of the given element.
	     *
	     * If an object is provided for `predicate` the created "_.matches" style
	     * callback returns `true` for elements that have the properties of the given
	     * object, else `false`.
	     *
	     * @static
	     * @memberOf _
	     * @category Collection
	     * @param {Array|Object|string} collection The collection to iterate over.
	     * @param {Function|Object|string} [iteratee] The function invoked per iteration.
	     *  If a property name or object is provided it is used to create a "_.property"
	     *  or "_.matches" style callback respectively.
	     * @param {*} [thisArg] The `this` binding of `iteratee`.
	     * @returns {*} Returns the maximum value.
	     * @example
	     *
	     * _.max([4, 2, 8, 6]);
	     * // => 8
	     *
	     * _.max([]);
	     * // => -Infinity
	     *
	     * var users = [
	     *   { 'user': 'barney', 'age': 36 },
	     *   { 'user': 'fred',   'age': 40 }
	     * ];
	     *
	     * _.max(users, function(chr) { return chr.age; });
	     * // => { 'user': 'fred', 'age': 40 };
	     *
	     * // using the "_.property" callback shorthand
	     * _.max(users, 'age');
	     * // => { 'user': 'fred', 'age': 40 };
	     */
	    var max = createExtremum(arrayMax);

	    /**
	     * Gets the minimum value of `collection`. If `collection` is empty or falsey
	     * `Infinity` is returned. If an iteratee function is provided it is invoked
	     * for each value in `collection` to generate the criterion by which the value
	     * is ranked. The `iteratee` is bound to `thisArg` and invoked with three
	     * arguments; (value, index, collection).
	     *
	     * If a property name is provided for `predicate` the created "_.property"
	     * style callback returns the property value of the given element.
	     *
	     * If an object is provided for `predicate` the created "_.matches" style
	     * callback returns `true` for elements that have the properties of the given
	     * object, else `false`.
	     *
	     * @static
	     * @memberOf _
	     * @category Collection
	     * @param {Array|Object|string} collection The collection to iterate over.
	     * @param {Function|Object|string} [iteratee] The function invoked per iteration.
	     *  If a property name or object is provided it is used to create a "_.property"
	     *  or "_.matches" style callback respectively.
	     * @param {*} [thisArg] The `this` binding of `iteratee`.
	     * @returns {*} Returns the minimum value.
	     * @example
	     *
	     * _.min([4, 2, 8, 6]);
	     * // => 2
	     *
	     * _.min([]);
	     * // => Infinity
	     *
	     * var users = [
	     *   { 'user': 'barney', 'age': 36 },
	     *   { 'user': 'fred',   'age': 40 }
	     * ];
	     *
	     * _.min(users, function(chr) { return chr.age; });
	     * // => { 'user': 'barney', 'age': 36 };
	     *
	     * // using the "_.property" callback shorthand
	     * _.min(users, 'age');
	     * // => { 'user': 'barney', 'age': 36 };
	     */
	    var min = createExtremum(arrayMin, true);

	    /**
	     * Creates an array of elements split into two groups, the first of which
	     * contains elements `predicate` returns truthy for, while the second of which
	     * contains elements `predicate` returns falsey for. The predicate is bound
	     * to `thisArg` and invoked with three arguments; (value, index|key, collection).
	     *
	     * If a property name is provided for `predicate` the created "_.property"
	     * style callback returns the property value of the given element.
	     *
	     * If an object is provided for `predicate` the created "_.matches" style
	     * callback returns `true` for elements that have the properties of the given
	     * object, else `false`.
	     *
	     * @static
	     * @memberOf _
	     * @category Collection
	     * @param {Array|Object|string} collection The collection to iterate over.
	     * @param {Function|Object|string} [predicate=_.identity] The function invoked
	     *  per iteration. If a property name or object is provided it is used to
	     *  create a "_.property" or "_.matches" style callback respectively.
	     * @param {*} [thisArg] The `this` binding of `predicate`.
	     * @returns {Array} Returns the array of grouped elements.
	     * @example
	     *
	     * _.partition([1, 2, 3], function(n) { return n % 2; });
	     * // => [[1, 3], [2]]
	     *
	     * _.partition([1.2, 2.3, 3.4], function(n) { return this.floor(n) % 2; }, Math);
	     * // => [[1, 3], [2]]
	     *
	     * var users = [
	     *   { 'user': 'barney',  'age': 36, 'active': false },
	     *   { 'user': 'fred',    'age': 40, 'active': true },
	     *   { 'user': 'pebbles', 'age': 1,  'active': false }
	     * ];
	     *
	     * // using the "_.matches" callback shorthand
	     * _.map(_.partition(users, { 'age': 1 }), function(array) { return _.pluck(array, 'user'); });
	     * // => [['pebbles'], ['barney', 'fred']]
	     *
	     * // using the "_.property" callback shorthand
	     * _.map(_.partition(users, 'active'), function(array) { return _.pluck(array, 'user'); });
	     * // => [['fred'], ['barney', 'pebbles']]
	     */
	    var partition = createAggregator(function(result, value, key) {
	      result[key ? 0 : 1].push(value);
	    }, function() { return [[], []]; });

	    /**
	     * Gets the value of `key` from all elements in `collection`.
	     *
	     * @static
	     * @memberOf _
	     * @category Collection
	     * @param {Array|Object|string} collection The collection to iterate over.
	     * @param {string} key The key of the property to pluck.
	     * @returns {Array} Returns the property values.
	     * @example
	     *
	     * var users = [
	     *   { 'user': 'barney', 'age': 36 },
	     *   { 'user': 'fred',   'age': 40 }
	     * ];
	     *
	     * _.pluck(users, 'user');
	     * // => ['barney', 'fred']
	     *
	     * var userIndex = _.indexBy(users, 'user');
	     * _.pluck(userIndex, 'age');
	     * // => [36, 40] (iteration order is not guaranteed)
	     */
	    function pluck(collection, key) {
	      return map(collection, baseProperty(key + ''));
	    }

	    /**
	     * Reduces `collection` to a value which is the accumulated result of running
	     * each element in `collection` through `iteratee`, where each successive
	     * invocation is supplied the return value of the previous. If `accumulator`
	     * is not provided the first element of `collection` is used as the initial
	     * value. The `iteratee` is bound to `thisArg`and invoked with four arguments;
	     * (accumulator, value, index|key, collection).
	     *
	     * @static
	     * @memberOf _
	     * @alias foldl, inject
	     * @category Collection
	     * @param {Array|Object|string} collection The collection to iterate over.
	     * @param {Function} [iteratee=_.identity] The function invoked per iteration.
	     * @param {*} [accumulator] The initial value.
	     * @param {*} [thisArg] The `this` binding of `iteratee`.
	     * @returns {*} Returns the accumulated value.
	     * @example
	     *
	     * var sum = _.reduce([1, 2, 3], function(sum, n) { return sum + n; });
	     * // => 6
	     *
	     * var mapped = _.reduce({ 'a': 1, 'b': 2, 'c': 3 }, function(result, n, key) {
	     *   result[key] = n * 3;
	     *   return result;
	     * }, {});
	     * // => { 'a': 3, 'b': 6, 'c': 9 } (iteration order is not guaranteed)
	     */
	    function reduce(collection, iteratee, accumulator, thisArg) {
	      var func = isArray(collection) ? arrayReduce : baseReduce;
	      return func(collection, getCallback(iteratee, thisArg, 4), accumulator, arguments.length < 3, baseEach);
	    }

	    /**
	     * This method is like `_.reduce` except that it iterates over elements of
	     * `collection` from right to left.
	     *
	     * @static
	     * @memberOf _
	     * @alias foldr
	     * @category Collection
	     * @param {Array|Object|string} collection The collection to iterate over.
	     * @param {Function} [iteratee=_.identity] The function invoked per iteration.
	     * @param {*} [accumulator] The initial value.
	     * @param {*} [thisArg] The `this` binding of `iteratee`.
	     * @returns {*} Returns the accumulated value.
	     * @example
	     *
	     * var array = [[0, 1], [2, 3], [4, 5]];
	     * _.reduceRight(array, function(flattened, other) { return flattened.concat(other); }, []);
	     * // => [4, 5, 2, 3, 0, 1]
	     */
	    function reduceRight(collection, iteratee, accumulator, thisArg) {
	      var func = isArray(collection) ? arrayReduceRight : baseReduce;
	      return func(collection, getCallback(iteratee, thisArg, 4), accumulator, arguments.length < 3, baseEachRight);
	    }

	    /**
	     * The opposite of `_.filter`; this method returns the elements of `collection`
	     * that `predicate` does **not** return truthy for.
	     *
	     * If a property name is provided for `predicate` the created "_.property"
	     * style callback returns the property value of the given element.
	     *
	     * If an object is provided for `predicate` the created "_.matches" style
	     * callback returns `true` for elements that have the properties of the given
	     * object, else `false`.
	     *
	     * @static
	     * @memberOf _
	     * @category Collection
	     * @param {Array|Object|string} collection The collection to iterate over.
	     * @param {Function|Object|string} [predicate=_.identity] The function invoked
	     *  per iteration. If a property name or object is provided it is used to
	     *  create a "_.property" or "_.matches" style callback respectively.
	     * @param {*} [thisArg] The `this` binding of `predicate`.
	     * @returns {Array} Returns the new filtered array.
	     * @example
	     *
	     * var odds = _.reject([1, 2, 3, 4], function(n) { return n % 2 == 0; });
	     * // => [1, 3]
	     *
	     * var users = [
	     *   { 'user': 'barney', 'age': 36, 'active': false },
	     *   { 'user': 'fred',   'age': 40, 'active': true }
	     * ];
	     *
	     * // using the "_.property" callback shorthand
	     * _.pluck(_.reject(users, 'active'), 'user');
	     * // => ['barney']
	     *
	     * // using the "_.matches" callback shorthand
	     * _.pluck(_.reject(users, { 'age': 36 }), 'user');
	     * // => ['fred']
	     */
	    function reject(collection, predicate, thisArg) {
	      var func = isArray(collection) ? arrayFilter : baseFilter;
	      predicate = getCallback(predicate, thisArg, 3);
	      return func(collection, function(value, index, collection) {
	        return !predicate(value, index, collection);
	      });
	    }

	    /**
	     * Gets a random element or `n` random elements from a collection.
	     *
	     * @static
	     * @memberOf _
	     * @category Collection
	     * @param {Array|Object|string} collection The collection to sample.
	     * @param {number} [n] The number of elements to sample.
	     * @param- {Object} [guard] Enables use as a callback for functions like `_.map`.
	     * @returns {*} Returns the random sample(s).
	     * @example
	     *
	     * _.sample([1, 2, 3, 4]);
	     * // => 2
	     *
	     * _.sample([1, 2, 3, 4], 2);
	     * // => [3, 1]
	     */
	    function sample(collection, n, guard) {
	      if (guard ? isIterateeCall(collection, n, guard) : n == null) {
	        collection = toIterable(collection);
	        var length = collection.length;
	        return length > 0 ? collection[baseRandom(0, length - 1)] : undefined;
	      }
	      var result = shuffle(collection);
	      result.length = nativeMin(n < 0 ? 0 : (+n || 0), result.length);
	      return result;
	    }

	    /**
	     * Creates an array of shuffled values, using a version of the Fisher-Yates
	     * shuffle. See [Wikipedia](https://en.wikipedia.org/wiki/Fisher-Yates_shuffle)
	     * for more details.
	     *
	     * @static
	     * @memberOf _
	     * @category Collection
	     * @param {Array|Object|string} collection The collection to shuffle.
	     * @returns {Array} Returns the new shuffled array.
	     * @example
	     *
	     * _.shuffle([1, 2, 3, 4]);
	     * // => [4, 1, 3, 2]
	     */
	    function shuffle(collection) {
	      collection = toIterable(collection);

	      var index = -1,
	          length = collection.length,
	          result = Array(length);

	      while (++index < length) {
	        var rand = baseRandom(0, index);
	        if (index != rand) {
	          result[index] = result[rand];
	        }
	        result[rand] = collection[index];
	      }
	      return result;
	    }

	    /**
	     * Gets the size of `collection` by returning `collection.length` for
	     * array-like values or the number of own enumerable properties for objects.
	     *
	     * @static
	     * @memberOf _
	     * @category Collection
	     * @param {Array|Object|string} collection The collection to inspect.
	     * @returns {number} Returns the size of `collection`.
	     * @example
	     *
	     * _.size([1, 2]);
	     * // => 2
	     *
	     * _.size({ 'one': 1, 'two': 2, 'three': 3 });
	     * // => 3
	     *
	     * _.size('pebbles');
	     * // => 7
	     */
	    function size(collection) {
	      var length = collection ? collection.length : 0;
	      return isLength(length) ? length : keys(collection).length;
	    }

	    /**
	     * Checks if `predicate` returns truthy for **any** element of `collection`.
	     * The function returns as soon as it finds a passing value and does not iterate
	     * over the entire collection. The predicate is bound to `thisArg` and invoked
	     * with three arguments; (value, index|key, collection).
	     *
	     * If a property name is provided for `predicate` the created "_.property"
	     * style callback returns the property value of the given element.
	     *
	     * If an object is provided for `predicate` the created "_.matches" style
	     * callback returns `true` for elements that have the properties of the given
	     * object, else `false`.
	     *
	     * @static
	     * @memberOf _
	     * @alias any
	     * @category Collection
	     * @param {Array|Object|string} collection The collection to iterate over.
	     * @param {Function|Object|string} [predicate=_.identity] The function invoked
	     *  per iteration. If a property name or object is provided it is used to
	     *  create a "_.property" or "_.matches" style callback respectively.
	     * @param {*} [thisArg] The `this` binding of `predicate`.
	     * @returns {boolean} Returns `true` if any element passes the predicate check,
	     *  else `false`.
	     * @example
	     *
	     * _.some([null, 0, 'yes', false], Boolean);
	     * // => true
	     *
	     * var users = [
	     *   { 'user': 'barney', 'age': 36, 'active': false },
	     *   { 'user': 'fred',   'age': 40, 'active': true }
	     * ];
	     *
	     * // using the "_.property" callback shorthand
	     * _.some(users, 'active');
	     * // => true
	     *
	     * // using the "_.matches" callback shorthand
	     * _.some(users, { 'age': 1 });
	     * // => false
	     */
	    function some(collection, predicate, thisArg) {
	      var func = isArray(collection) ? arraySome : baseSome;
	      if (typeof predicate != 'function' || typeof thisArg != 'undefined') {
	        predicate = getCallback(predicate, thisArg, 3);
	      }
	      return func(collection, predicate);
	    }

	    /**
	     * Creates an array of elements, sorted in ascending order by the results of
	     * running each element in a collection through `iteratee`. This method performs
	     * a stable sort, that is, it preserves the original sort order of equal elements.
	     * The `iteratee` is bound to `thisArg` and invoked with three arguments;
	     * (value, index|key, collection).
	     *
	     * If a property name is provided for `predicate` the created "_.property"
	     * style callback returns the property value of the given element.
	     *
	     * If an object is provided for `predicate` the created "_.matches" style
	     * callback returns `true` for elements that have the properties of the given
	     * object, else `false`.
	     *
	     * @static
	     * @memberOf _
	     * @category Collection
	     * @param {Array|Object|string} collection The collection to iterate over.
	     * @param {Array|Function|Object|string} [iteratee=_.identity] The function
	     *  invoked per iteration. If a property name or an object is provided it is
	     *  used to create a "_.property" or "_.matches" style callback respectively.
	     * @param {*} [thisArg] The `this` binding of `iteratee`.
	     * @returns {Array} Returns the new sorted array.
	     * @example
	     *
	     * _.sortBy([1, 2, 3], function(n) { return Math.sin(n); });
	     * // => [3, 1, 2]
	     *
	     * _.sortBy([1, 2, 3], function(n) { return this.sin(n); }, Math);
	     * // => [3, 1, 2]
	     *
	     * var users = [
	     *   { 'user': 'fred' },
	     *   { 'user': 'pebbles' },
	     *   { 'user': 'barney' }
	     * ];
	     *
	     * // using the "_.property" callback shorthand
	     * _.pluck(_.sortBy(users, 'user'), 'user');
	     * // => ['barney', 'fred', 'pebbles']
	     */
	    function sortBy(collection, iteratee, thisArg) {
	      var index = -1,
	          length = collection ? collection.length : 0,
	          result = isLength(length) ? Array(length) : [];

	      if (thisArg && isIterateeCall(collection, iteratee, thisArg)) {
	        iteratee = null;
	      }
	      iteratee = getCallback(iteratee, thisArg, 3);
	      baseEach(collection, function(value, key, collection) {
	        result[++index] = { 'criteria': iteratee(value, key, collection), 'index': index, 'value': value };
	      });
	      return baseSortBy(result, compareAscending);
	    }

	    /**
	     * This method is like `_.sortBy` except that it sorts by property names
	     * instead of an iteratee function.
	     *
	     * @static
	     * @memberOf _
	     * @category Collection
	     * @param {Array|Object|string} collection The collection to iterate over.
	     * @param {...(string|string[])} props The property names to sort by,
	     *  specified as individual property names or arrays of property names.
	     * @returns {Array} Returns the new sorted array.
	     * @example
	     *
	     * var users = [
	     *   { 'user': 'barney', 'age': 36 },
	     *   { 'user': 'fred',   'age': 40 },
	     *   { 'user': 'barney', 'age': 26 },
	     *   { 'user': 'fred',   'age': 30 }
	     * ];
	     *
	     * _.map(_.sortByAll(users, ['user', 'age']), _.values);
	     * // => [['barney', 26], ['barney', 36], ['fred', 30], ['fred', 40]]
	     */
	    function sortByAll(collection) {
	      var args = arguments;
	      if (args.length > 3 && isIterateeCall(args[1], args[2], args[3])) {
	        args = [collection, args[1]];
	      }
	      var index = -1,
	          length = collection ? collection.length : 0,
	          props = baseFlatten(args, false, false, 1),
	          result = isLength(length) ? Array(length) : [];

	      baseEach(collection, function(value, key, collection) {
	        var length = props.length,
	            criteria = Array(length);

	        while (length--) {
	          criteria[length] = value == null ? undefined : value[props[length]];
	        }
	        result[++index] = { 'criteria': criteria, 'index': index, 'value': value };
	      });
	      return baseSortBy(result, compareMultipleAscending);
	    }

	    /**
	     * Performs a deep comparison between each element in `collection` and the
	     * source object, returning an array of all elements that have equivalent
	     * property values.
	     *
	     * @static
	     * @memberOf _
	     * @category Collection
	     * @param {Array|Object|string} collection The collection to search.
	     * @param {Object} source The object of property values to match.
	     * @returns {Array} Returns the new filtered array.
	     * @example
	     *
	     * var users = [
	     *   { 'user': 'barney', 'age': 36, 'status': 'busy', 'pets': ['hoppy'] },
	     *   { 'user': 'fred',   'age': 40, 'status': 'busy', 'pets': ['baby puss', 'dino'] }
	     * ];
	     *
	     * _.pluck(_.where(users, { 'age': 36 }), 'user');
	     * // => ['barney']
	     *
	     * _.pluck(_.where(users, { 'pets': ['dino'] }), 'user');
	     * // => ['fred']
	     *
	     * _.pluck(_.where(users, { 'status': 'busy' }), 'user');
	     * // => ['barney', 'fred']
	     */
	    function where(collection, source) {
	      return filter(collection, baseMatches(source));
	    }

	    /*------------------------------------------------------------------------*/

	    /**
	     * Gets the number of milliseconds that have elapsed since the Unix epoch
	     * (1 January 1970 00:00:00 UTC).
	     *
	     * @static
	     * @memberOf _
	     * @category Date
	     * @example
	     *
	     * _.defer(function(stamp) { console.log(_.now() - stamp); }, _.now());
	     * // => logs the number of milliseconds it took for the deferred function to be invoked
	     */
	    var now = nativeNow || function() {
	      return new Date().getTime();
	    };

	    /*------------------------------------------------------------------------*/

	    /**
	     * The opposite of `_.before`; this method creates a function that invokes
	     * `func` once it is called `n` or more times.
	     *
	     * @static
	     * @memberOf _
	     * @category Function
	     * @param {number} n The number of calls before `func` is invoked.
	     * @param {Function} func The function to restrict.
	     * @returns {Function} Returns the new restricted function.
	     * @example
	     *
	     * var saves = ['profile', 'settings'];
	     *
	     * var done = _.after(saves.length, function() {
	     *   console.log('done saving!');
	     * });
	     *
	     * _.forEach(saves, function(type) {
	     *   asyncSave({ 'type': type, 'complete': done });
	     * });
	     * // => logs 'done saving!' after the two async saves have completed
	     */
	    function after(n, func) {
	      if (!isFunction(func)) {
	        if (isFunction(n)) {
	          var temp = n;
	          n = func;
	          func = temp;
	        } else {
	          throw new TypeError(FUNC_ERROR_TEXT);
	        }
	      }
	      n = nativeIsFinite(n = +n) ? n : 0;
	      return function() {
	        if (--n < 1) {
	          return func.apply(this, arguments);
	        }
	      };
	    }

	    /**
	     * Creates a function that accepts up to `n` arguments ignoring any
	     * additional arguments.
	     *
	     * @static
	     * @memberOf _
	     * @category Function
	     * @param {Function} func The function to cap arguments for.
	     * @param {number} [n=func.length] The arity cap.
	     * @param- {Object} [guard] Enables use as a callback for functions like `_.map`.
	     * @returns {Function} Returns the new function.
	     * @example
	     *
	     * _.map(['6', '8', '10'], _.ary(parseInt, 1));
	     * // => [6, 8, 10]
	     */
	    function ary(func, n, guard) {
	      if (guard && isIterateeCall(func, n, guard)) {
	        n = null;
	      }
	      n = (func && n == null) ? func.length : nativeMax(+n || 0, 0);
	      return createWrapper(func, ARY_FLAG, null, null, null, null, n);
	    }

	    /**
	     * Creates a function that invokes `func`, with the `this` binding and arguments
	     * of the created function, while it is called less than `n` times. Subsequent
	     * calls to the created function return the result of the last `func` invocation.
	     *
	     * @static
	     * @memberOf _
	     * @category Function
	     * @param {number} n The number of calls at which `func` is no longer invoked.
	     * @param {Function} func The function to restrict.
	     * @returns {Function} Returns the new restricted function.
	     * @example
	     *
	     * jQuery('#add').on('click', _.before(5, addContactToList));
	     * // => allows adding up to 4 contacts to the list
	     */
	    function before(n, func) {
	      var result;
	      if (!isFunction(func)) {
	        if (isFunction(n)) {
	          var temp = n;
	          n = func;
	          func = temp;
	        } else {
	          throw new TypeError(FUNC_ERROR_TEXT);
	        }
	      }
	      return function() {
	        if (--n > 0) {
	          result = func.apply(this, arguments);
	        } else {
	          func = null;
	        }
	        return result;
	      };
	    }

	    /**
	     * Creates a function that invokes `func` with the `this` binding of `thisArg`
	     * and prepends any additional `_.bind` arguments to those provided to the
	     * bound function.
	     *
	     * The `_.bind.placeholder` value, which defaults to `_` in monolithic builds,
	     * may be used as a placeholder for partially applied arguments.
	     *
	     * **Note:** Unlike native `Function#bind` this method does not set the `length`
	     * property of bound functions.
	     *
	     * @static
	     * @memberOf _
	     * @category Function
	     * @param {Function} func The function to bind.
	     * @param {*} thisArg The `this` binding of `func`.
	     * @param {...*} [args] The arguments to be partially applied.
	     * @returns {Function} Returns the new bound function.
	     * @example
	     *
	     * var greet = function(greeting, punctuation) {
	     *   return greeting + ' ' + this.user + punctuation;
	     * };
	     *
	     * var object = { 'user': 'fred' };
	     *
	     * var bound = _.bind(greet, object, 'hi');
	     * bound('!');
	     * // => 'hi fred!'
	     *
	     * // using placeholders
	     * var bound = _.bind(greet, object, _, '!');
	     * bound('hi');
	     * // => 'hi fred!'
	     */
	    function bind(func, thisArg) {
	      var bitmask = BIND_FLAG;
	      if (arguments.length > 2) {
	        var partials = baseSlice(arguments, 2),
	            holders = replaceHolders(partials, bind.placeholder);

	        bitmask |= PARTIAL_FLAG;
	      }
	      return createWrapper(func, bitmask, thisArg, partials, holders);
	    }

	    /**
	     * Binds methods of an object to the object itself, overwriting the existing
	     * method. Method names may be specified as individual arguments or as arrays
	     * of method names. If no method names are provided all enumerable function
	     * properties, own and inherited, of `object` are bound.
	     *
	     * **Note:** This method does not set the `length` property of bound functions.
	     *
	     * @static
	     * @memberOf _
	     * @category Function
	     * @param {Object} object The object to bind and assign the bound methods to.
	     * @param {...(string|string[])} [methodNames] The object method names to bind,
	     *  specified as individual method names or arrays of method names.
	     * @returns {Object} Returns `object`.
	     * @example
	     *
	     * var view = {
	     *   'label': 'docs',
	     *   'onClick': function() { console.log('clicked ' + this.label); }
	     * };
	     *
	     * _.bindAll(view);
	     * jQuery('#docs').on('click', view.onClick);
	     * // => logs 'clicked docs' when the element is clicked
	     */
	    function bindAll(object) {
	      return baseBindAll(object,
	        arguments.length > 1
	          ? baseFlatten(arguments, false, false, 1)
	          : functions(object)
	      );
	    }

	    /**
	     * Creates a function that invokes the method at `object[key]` and prepends
	     * any additional `_.bindKey` arguments to those provided to the bound function.
	     *
	     * This method differs from `_.bind` by allowing bound functions to reference
	     * methods that may be redefined or don't yet exist.
	     * See [Peter Michaux's article](http://michaux.ca/articles/lazy-function-definition-pattern)
	     * for more details.
	     *
	     * The `_.bindKey.placeholder` value, which defaults to `_` in monolithic
	     * builds, may be used as a placeholder for partially applied arguments.
	     *
	     * @static
	     * @memberOf _
	     * @category Function
	     * @param {Object} object The object the method belongs to.
	     * @param {string} key The key of the method.
	     * @param {...*} [args] The arguments to be partially applied.
	     * @returns {Function} Returns the new bound function.
	     * @example
	     *
	     * var object = {
	     *   'user': 'fred',
	     *   'greet': function(greeting, punctuation) {
	     *     return greeting + ' ' + this.user + punctuation;
	     *   }
	     * };
	     *
	     * var bound = _.bindKey(object, 'greet', 'hi');
	     * bound('!');
	     * // => 'hi fred!'
	     *
	     * object.greet = function(greeting, punctuation) {
	     *   return greeting + 'ya ' + this.user + punctuation;
	     * };
	     *
	     * bound('!');
	     * // => 'hiya fred!'
	     *
	     * // using placeholders
	     * var bound = _.bindKey(object, 'greet', _, '!');
	     * bound('hi');
	     * // => 'hiya fred!'
	     */
	    function bindKey(object, key) {
	      var bitmask = BIND_FLAG | BIND_KEY_FLAG;
	      if (arguments.length > 2) {
	        var partials = baseSlice(arguments, 2),
	            holders = replaceHolders(partials, bindKey.placeholder);

	        bitmask |= PARTIAL_FLAG;
	      }
	      return createWrapper(key, bitmask, object, partials, holders);
	    }

	    /**
	     * Creates a function that accepts one or more arguments of `func` that when
	     * called either invokes `func` returning its result, if all `func` arguments
	     * have been provided, or returns a function that accepts one or more of the
	     * remaining `func` arguments, and so on. The arity of `func` may be specified
	     * if `func.length` is not sufficient.
	     *
	     * The `_.curry.placeholder` value, which defaults to `_` in monolithic builds,
	     * may be used as a placeholder for provided arguments.
	     *
	     * **Note:** This method does not set the `length` property of curried functions.
	     *
	     * @static
	     * @memberOf _
	     * @category Function
	     * @param {Function} func The function to curry.
	     * @param {number} [arity=func.length] The arity of `func`.
	     * @param- {Object} [guard] Enables use as a callback for functions like `_.map`.
	     * @returns {Function} Returns the new curried function.
	     * @example
	     *
	     * var abc = function(a, b, c) {
	     *   return [a, b, c];
	     * };
	     *
	     * var curried = _.curry(abc);
	     *
	     * curried(1)(2)(3);
	     * // => [1, 2, 3]
	     *
	     * curried(1, 2)(3);
	     * // => [1, 2, 3]
	     *
	     * curried(1, 2, 3);
	     * // => [1, 2, 3]
	     *
	     * // using placeholders
	     * curried(1)(_, 3)(2);
	     * // => [1, 2, 3]
	     */
	    function curry(func, arity, guard) {
	      if (guard && isIterateeCall(func, arity, guard)) {
	        arity = null;
	      }
	      var result = createWrapper(func, CURRY_FLAG, null, null, null, null, null, arity);
	      result.placeholder = curry.placeholder;
	      return result;
	    }

	    /**
	     * This method is like `_.curry` except that arguments are applied to `func`
	     * in the manner of `_.partialRight` instead of `_.partial`.
	     *
	     * The `_.curryRight.placeholder` value, which defaults to `_` in monolithic
	     * builds, may be used as a placeholder for provided arguments.
	     *
	     * **Note:** This method does not set the `length` property of curried functions.
	     *
	     * @static
	     * @memberOf _
	     * @category Function
	     * @param {Function} func The function to curry.
	     * @param {number} [arity=func.length] The arity of `func`.
	     * @param- {Object} [guard] Enables use as a callback for functions like `_.map`.
	     * @returns {Function} Returns the new curried function.
	     * @example
	     *
	     * var abc = function(a, b, c) {
	     *   return [a, b, c];
	     * };
	     *
	     * var curried = _.curryRight(abc);
	     *
	     * curried(3)(2)(1);
	     * // => [1, 2, 3]
	     *
	     * curried(2, 3)(1);
	     * // => [1, 2, 3]
	     *
	     * curried(1, 2, 3);
	     * // => [1, 2, 3]
	     *
	     * // using placeholders
	     * curried(3)(1, _)(2);
	     * // => [1, 2, 3]
	     */
	    function curryRight(func, arity, guard) {
	      if (guard && isIterateeCall(func, arity, guard)) {
	        arity = null;
	      }
	      var result = createWrapper(func, CURRY_RIGHT_FLAG, null, null, null, null, null, arity);
	      result.placeholder = curryRight.placeholder;
	      return result;
	    }

	    /**
	     * Creates a function that delays invoking `func` until after `wait` milliseconds
	     * have elapsed since the last time it was invoked. The created function comes
	     * with a `cancel` method to cancel delayed invocations. Provide an options
	     * object to indicate that `func` should be invoked on the leading and/or
	     * trailing edge of the `wait` timeout. Subsequent calls to the debounced
	     * function return the result of the last `func` invocation.
	     *
	     * **Note:** If `leading` and `trailing` options are `true`, `func` is invoked
	     * on the trailing edge of the timeout only if the the debounced function is
	     * invoked more than once during the `wait` timeout.
	     *
	     * See [David Corbacho's article](http://drupalmotion.com/article/debounce-and-throttle-visual-explanation)
	     * for details over the differences between `_.debounce` and `_.throttle`.
	     *
	     * @static
	     * @memberOf _
	     * @category Function
	     * @param {Function} func The function to debounce.
	     * @param {number} wait The number of milliseconds to delay.
	     * @param {Object} [options] The options object.
	     * @param {boolean} [options.leading=false] Specify invoking on the leading
	     *  edge of the timeout.
	     * @param {number} [options.maxWait] The maximum time `func` is allowed to be
	     *  delayed before it is invoked.
	     * @param {boolean} [options.trailing=true] Specify invoking on the trailing
	     *  edge of the timeout.
	     * @returns {Function} Returns the new debounced function.
	     * @example
	     *
	     * // avoid costly calculations while the window size is in flux
	     * jQuery(window).on('resize', _.debounce(calculateLayout, 150));
	     *
	     * // invoke `sendMail` when the click event is fired, debouncing subsequent calls
	     * jQuery('#postbox').on('click', _.debounce(sendMail, 300, {
	     *   'leading': true,
	     *   'trailing': false
	     * }));
	     *
	     * // ensure `batchLog` is invoked once after 1 second of debounced calls
	     * var source = new EventSource('/stream');
	     * jQuery(source).on('message', _.debounce(batchLog, 250, {
	     *   'maxWait': 1000
	     * }));
	     *
	     * // cancel a debounced call
	     * var todoChanges = _.debounce(batchLog, 1000);
	     * Object.observe(models.todo, todoChanges);
	     *
	     * Object.observe(models, function(changes) {
	     *   if (_.find(changes, { 'user': 'todo', 'type': 'delete'})) {
	     *     todoChanges.cancel();
	     *   }
	     * }, ['delete']);
	     *
	     * // ...at some point `models.todo` is changed
	     * models.todo.completed = true;
	     *
	     * // ...before 1 second has passed `models.todo` is deleted
	     * // which cancels the debounced `todoChanges` call
	     * delete models.todo;
	     */
	    function debounce(func, wait, options) {
	      var args,
	          maxTimeoutId,
	          result,
	          stamp,
	          thisArg,
	          timeoutId,
	          trailingCall,
	          lastCalled = 0,
	          maxWait = false,
	          trailing = true;

	      if (!isFunction(func)) {
	        throw new TypeError(FUNC_ERROR_TEXT);
	      }
	      wait = wait < 0 ? 0 : wait;
	      if (options === true) {
	        var leading = true;
	        trailing = false;
	      } else if (isObject(options)) {
	        leading = options.leading;
	        maxWait = 'maxWait' in options && nativeMax(+options.maxWait || 0, wait);
	        trailing = 'trailing' in options ? options.trailing : trailing;
	      }

	      function cancel() {
	        if (timeoutId) {
	          clearTimeout(timeoutId);
	        }
	        if (maxTimeoutId) {
	          clearTimeout(maxTimeoutId);
	        }
	        maxTimeoutId = timeoutId = trailingCall = undefined;
	      }

	      function delayed() {
	        var remaining = wait - (now() - stamp);
	        if (remaining <= 0 || remaining > wait) {
	          if (maxTimeoutId) {
	            clearTimeout(maxTimeoutId);
	          }
	          var isCalled = trailingCall;
	          maxTimeoutId = timeoutId = trailingCall = undefined;
	          if (isCalled) {
	            lastCalled = now();
	            result = func.apply(thisArg, args);
	            if (!timeoutId && !maxTimeoutId) {
	              args = thisArg = null;
	            }
	          }
	        } else {
	          timeoutId = setTimeout(delayed, remaining);
	        }
	      }

	      function maxDelayed() {
	        if (timeoutId) {
	          clearTimeout(timeoutId);
	        }
	        maxTimeoutId = timeoutId = trailingCall = undefined;
	        if (trailing || (maxWait !== wait)) {
	          lastCalled = now();
	          result = func.apply(thisArg, args);
	          if (!timeoutId && !maxTimeoutId) {
	            args = thisArg = null;
	          }
	        }
	      }

	      function debounced() {
	        args = arguments;
	        stamp = now();
	        thisArg = this;
	        trailingCall = trailing && (timeoutId || !leading);

	        if (maxWait === false) {
	          var leadingCall = leading && !timeoutId;
	        } else {
	          if (!maxTimeoutId && !leading) {
	            lastCalled = stamp;
	          }
	          var remaining = maxWait - (stamp - lastCalled),
	              isCalled = remaining <= 0 || remaining > maxWait;

	          if (isCalled) {
	            if (maxTimeoutId) {
	              maxTimeoutId = clearTimeout(maxTimeoutId);
	            }
	            lastCalled = stamp;
	            result = func.apply(thisArg, args);
	          }
	          else if (!maxTimeoutId) {
	            maxTimeoutId = setTimeout(maxDelayed, remaining);
	          }
	        }
	        if (isCalled && timeoutId) {
	          timeoutId = clearTimeout(timeoutId);
	        }
	        else if (!timeoutId && wait !== maxWait) {
	          timeoutId = setTimeout(delayed, wait);
	        }
	        if (leadingCall) {
	          isCalled = true;
	          result = func.apply(thisArg, args);
	        }
	        if (isCalled && !timeoutId && !maxTimeoutId) {
	          args = thisArg = null;
	        }
	        return result;
	      }
	      debounced.cancel = cancel;
	      return debounced;
	    }

	    /**
	     * Defers invoking the `func` until the current call stack has cleared. Any
	     * additional arguments are provided to `func` when it is invoked.
	     *
	     * @static
	     * @memberOf _
	     * @category Function
	     * @param {Function} func The function to defer.
	     * @param {...*} [args] The arguments to invoke the function with.
	     * @returns {number} Returns the timer id.
	     * @example
	     *
	     * _.defer(function(text) { console.log(text); }, 'deferred');
	     * // logs 'deferred' after one or more milliseconds
	     */
	    function defer(func) {
	      return baseDelay(func, 1, arguments, 1);
	    }

	    /**
	     * Invokes `func` after `wait` milliseconds. Any additional arguments are
	     * provided to `func` when it is invoked.
	     *
	     * @static
	     * @memberOf _
	     * @category Function
	     * @param {Function} func The function to delay.
	     * @param {number} wait The number of milliseconds to delay invocation.
	     * @param {...*} [args] The arguments to invoke the function with.
	     * @returns {number} Returns the timer id.
	     * @example
	     *
	     * _.delay(function(text) { console.log(text); }, 1000, 'later');
	     * // => logs 'later' after one second
	     */
	    function delay(func, wait) {
	      return baseDelay(func, wait, arguments, 2);
	    }

	    /**
	     * Creates a function that returns the result of invoking the provided
	     * functions with the `this` binding of the created function, where each
	     * successive invocation is supplied the return value of the previous.
	     *
	     * @static
	     * @memberOf _
	     * @category Function
	     * @param {...Function} [funcs] Functions to invoke.
	     * @returns {Function} Returns the new function.
	     * @example
	     *
	     * function add(x, y) {
	     *   return x + y;
	     * }
	     *
	     * function square(n) {
	     *   return n * n;
	     * }
	     *
	     * var addSquare = _.flow(add, square);
	     * addSquare(1, 2);
	     * // => 9
	     */
	    function flow() {
	      var funcs = arguments,
	          length = funcs.length;

	      if (!length) {
	        return function() {};
	      }
	      if (!arrayEvery(funcs, isFunction)) {
	        throw new TypeError(FUNC_ERROR_TEXT);
	      }
	      return function() {
	        var index = 0,
	            result = funcs[index].apply(this, arguments);

	        while (++index < length) {
	          result = funcs[index].call(this, result);
	        }
	        return result;
	      };
	    }

	    /**
	     * This method is like `_.flow` except that it creates a function that
	     * invokes the provided functions from right to left.
	     *
	     * @static
	     * @memberOf _
	     * @alias backflow, compose
	     * @category Function
	     * @param {...Function} [funcs] Functions to invoke.
	     * @returns {Function} Returns the new function.
	     * @example
	     *
	     * function add(x, y) {
	     *   return x + y;
	     * }
	     *
	     * function square(n) {
	     *   return n * n;
	     * }
	     *
	     * var addSquare = _.flowRight(square, add);
	     * addSquare(1, 2);
	     * // => 9
	     */
	    function flowRight() {
	      var funcs = arguments,
	          fromIndex = funcs.length - 1;

	      if (fromIndex < 0) {
	        return function() {};
	      }
	      if (!arrayEvery(funcs, isFunction)) {
	        throw new TypeError(FUNC_ERROR_TEXT);
	      }
	      return function() {
	        var index = fromIndex,
	            result = funcs[index].apply(this, arguments);

	        while (index--) {
	          result = funcs[index].call(this, result);
	        }
	        return result;
	      };
	    }

	    /**
	     * Creates a function that memoizes the result of `func`. If `resolver` is
	     * provided it determines the cache key for storing the result based on the
	     * arguments provided to the memoized function. By default, the first argument
	     * provided to the memoized function is coerced to a string and used as the
	     * cache key. The `func` is invoked with the `this` binding of the memoized
	     * function.
	     *
	     * **Note:** The cache is exposed as the `cache` property on the memoized
	     * function. Its creation may be customized by replacing the `_.memoize.Cache`
	     * constructor with one whose instances implement the ES `Map` method interface
	     * of `get`, `has`, and `set`. See the
	     * [ES spec](https://people.mozilla.org/~jorendorff/es6-draft.html#sec-properties-of-the-map-prototype-object)
	     * for more details.
	     *
	     * @static
	     * @memberOf _
	     * @category Function
	     * @param {Function} func The function to have its output memoized.
	     * @param {Function} [resolver] The function to resolve the cache key.
	     * @returns {Function} Returns the new memoizing function.
	     * @example
	     *
	     * var upperCase = _.memoize(function(string) {
	     *   return string.toUpperCase();
	     * });
	     *
	     * upperCase('fred');
	     * // => 'FRED'
	     *
	     * // modifying the result cache
	     * upperCase.cache.set('fred', 'BARNEY');
	     * upperCase('fred');
	     * // => 'BARNEY'
	     *
	     * // replacing `_.memoize.Cache`
	     * var object = { 'user': 'fred' };
	     * var other = { 'user': 'barney' };
	     * var identity = _.memoize(_.identity);
	     *
	     * identity(object);
	     * // => { 'user': 'fred' }
	     * identity(other);
	     * // => { 'user': 'fred' }
	     *
	     * _.memoize.Cache = WeakMap;
	     * var identity = _.memoize(_.identity);
	     *
	     * identity(object);
	     * // => { 'user': 'fred' }
	     * identity(other);
	     * // => { 'user': 'barney' }
	     */
	    function memoize(func, resolver) {
	      if (!isFunction(func) || (resolver && !isFunction(resolver))) {
	        throw new TypeError(FUNC_ERROR_TEXT);
	      }
	      var memoized = function() {
	        var cache = memoized.cache,
	            key = resolver ? resolver.apply(this, arguments) : arguments[0];

	        if (cache.has(key)) {
	          return cache.get(key);
	        }
	        var result = func.apply(this, arguments);
	        cache.set(key, result);
	        return result;
	      };
	      memoized.cache = new memoize.Cache;
	      return memoized;
	    }

	    /**
	     * Creates a function that negates the result of the predicate `func`. The
	     * `func` predicate is invoked with the `this` binding and arguments of the
	     * created function.
	     *
	     * @static
	     * @memberOf _
	     * @category Function
	     * @param {Function} predicate The predicate to negate.
	     * @returns {Function} Returns the new function.
	     * @example
	     *
	     * function isEven(n) {
	     *   return n % 2 == 0;
	     * }
	     *
	     * _.filter([1, 2, 3, 4, 5, 6], _.negate(isEven));
	     * // => [1, 3, 5]
	     */
	    function negate(predicate) {
	      if (!isFunction(predicate)) {
	        throw new TypeError(FUNC_ERROR_TEXT);
	      }
	      return function() {
	        return !predicate.apply(this, arguments);
	      };
	    }

	    /**
	     * Creates a function that is restricted to invoking `func` once. Repeat calls
	     * to the function return the value of the first call. The `func` is invoked
	     * with the `this` binding of the created function.
	     *
	     * @static
	     * @memberOf _
	     * @type Function
	     * @category Function
	     * @param {Function} func The function to restrict.
	     * @returns {Function} Returns the new restricted function.
	     * @example
	     *
	     * var initialize = _.once(createApplication);
	     * initialize();
	     * initialize();
	     * // `initialize` invokes `createApplication` once
	     */
	    function once(func) {
	      return before(func, 2);
	    }

	    /**
	     * Creates a function that invokes `func` with `partial` arguments prepended
	     * to those provided to the new function. This method is like `_.bind` except
	     * it does **not** alter the `this` binding.
	     *
	     * The `_.partial.placeholder` value, which defaults to `_` in monolithic
	     * builds, may be used as a placeholder for partially applied arguments.
	     *
	     * **Note:** This method does not set the `length` property of partially
	     * applied functions.
	     *
	     * @static
	     * @memberOf _
	     * @category Function
	     * @param {Function} func The function to partially apply arguments to.
	     * @param {...*} [args] The arguments to be partially applied.
	     * @returns {Function} Returns the new partially applied function.
	     * @example
	     *
	     * var greet = function(greeting, name) {
	     *   return greeting + ' ' + name;
	     * };
	     *
	     * var sayHelloTo = _.partial(greet, 'hello');
	     * sayHelloTo('fred');
	     * // => 'hello fred'
	     *
	     * // using placeholders
	     * var greetFred = _.partial(greet, _, 'fred');
	     * greetFred('hi');
	     * // => 'hi fred'
	     */
	    function partial(func) {
	      var partials = baseSlice(arguments, 1),
	          holders = replaceHolders(partials, partial.placeholder);

	      return createWrapper(func, PARTIAL_FLAG, null, partials, holders);
	    }

	    /**
	     * This method is like `_.partial` except that partially applied arguments
	     * are appended to those provided to the new function.
	     *
	     * The `_.partialRight.placeholder` value, which defaults to `_` in monolithic
	     * builds, may be used as a placeholder for partially applied arguments.
	     *
	     * **Note:** This method does not set the `length` property of partially
	     * applied functions.
	     *
	     * @static
	     * @memberOf _
	     * @category Function
	     * @param {Function} func The function to partially apply arguments to.
	     * @param {...*} [args] The arguments to be partially applied.
	     * @returns {Function} Returns the new partially applied function.
	     * @example
	     *
	     * var greet = function(greeting, name) {
	     *   return greeting + ' ' + name;
	     * };
	     *
	     * var greetFred = _.partialRight(greet, 'fred');
	     * greetFred('hi');
	     * // => 'hi fred'
	     *
	     * // using placeholders
	     * var sayHelloTo = _.partialRight(greet, 'hello', _);
	     * sayHelloTo('fred');
	     * // => 'hello fred'
	     */
	    function partialRight(func) {
	      var partials = baseSlice(arguments, 1),
	          holders = replaceHolders(partials, partialRight.placeholder);

	      return createWrapper(func, PARTIAL_RIGHT_FLAG, null, partials, holders);
	    }

	    /**
	     * Creates a function that invokes `func` with arguments arranged according
	     * to the specified indexes where the argument value at the first index is
	     * provided as the first argument, the argument value at the second index is
	     * provided as the second argument, and so on.
	     *
	     * @static
	     * @memberOf _
	     * @category Function
	     * @param {Function} func The function to rearrange arguments for.
	     * @param {...(number|number[])} indexes The arranged argument indexes,
	     *  specified as individual indexes or arrays of indexes.
	     * @returns {Function} Returns the new function.
	     * @example
	     *
	     * var rearged = _.rearg(function(a, b, c) {
	     *   return [a, b, c];
	     * }, 2, 0, 1);
	     *
	     * rearged('b', 'c', 'a')
	     * // => ['a', 'b', 'c']
	     *
	     * var map = _.rearg(_.map, [1, 0]);
	     * map(function(n) { return n * 3; }, [1, 2, 3]);
	     * // => [3, 6, 9]
	     */
	    function rearg(func) {
	      var indexes = baseFlatten(arguments, false, false, 1);
	      return createWrapper(func, REARG_FLAG, null, null, null, indexes);
	    }

	    /**
	     * Creates a function that only invokes `func` at most once per every `wait`
	     * milliseconds. The created function comes with a `cancel` method to cancel
	     * delayed invocations. Provide an options object to indicate that `func`
	     * should be invoked on the leading and/or trailing edge of the `wait` timeout.
	     * Subsequent calls to the throttled function return the result of the last
	     * `func` call.
	     *
	     * **Note:** If `leading` and `trailing` options are `true`, `func` is invoked
	     * on the trailing edge of the timeout only if the the throttled function is
	     * invoked more than once during the `wait` timeout.
	     *
	     * See [David Corbacho's article](http://drupalmotion.com/article/debounce-and-throttle-visual-explanation)
	     * for details over the differences between `_.throttle` and `_.debounce`.
	     *
	     * @static
	     * @memberOf _
	     * @category Function
	     * @param {Function} func The function to throttle.
	     * @param {number} wait The number of milliseconds to throttle invocations to.
	     * @param {Object} [options] The options object.
	     * @param {boolean} [options.leading=true] Specify invoking on the leading
	     *  edge of the timeout.
	     * @param {boolean} [options.trailing=true] Specify invoking on the trailing
	     *  edge of the timeout.
	     * @returns {Function} Returns the new throttled function.
	     * @example
	     *
	     * // avoid excessively updating the position while scrolling
	     * jQuery(window).on('scroll', _.throttle(updatePosition, 100));
	     *
	     * // invoke `renewToken` when the click event is fired, but not more than once every 5 minutes
	     * var throttled =  _.throttle(renewToken, 300000, { 'trailing': false })
	     * jQuery('.interactive').on('click', throttled);
	     *
	     * // cancel a trailing throttled call
	     * jQuery(window).on('popstate', throttled.cancel);
	     */
	    function throttle(func, wait, options) {
	      var leading = true,
	          trailing = true;

	      if (!isFunction(func)) {
	        throw new TypeError(FUNC_ERROR_TEXT);
	      }
	      if (options === false) {
	        leading = false;
	      } else if (isObject(options)) {
	        leading = 'leading' in options ? !!options.leading : leading;
	        trailing = 'trailing' in options ? !!options.trailing : trailing;
	      }
	      debounceOptions.leading = leading;
	      debounceOptions.maxWait = +wait;
	      debounceOptions.trailing = trailing;
	      return debounce(func, wait, debounceOptions);
	    }

	    /**
	     * Creates a function that provides `value` to the wrapper function as its
	     * first argument. Any additional arguments provided to the function are
	     * appended to those provided to the wrapper function. The wrapper is invoked
	     * with the `this` binding of the created function.
	     *
	     * @static
	     * @memberOf _
	     * @category Function
	     * @param {*} value The value to wrap.
	     * @param {Function} wrapper The wrapper function.
	     * @returns {Function} Returns the new function.
	     * @example
	     *
	     * var p = _.wrap(_.escape, function(func, text) {
	     *   return '<p>' + func(text) + '</p>';
	     * });
	     *
	     * p('fred, barney, & pebbles');
	     * // => '<p>fred, barney, &amp; pebbles</p>'
	     */
	    function wrap(value, wrapper) {
	      wrapper = wrapper == null ? identity : wrapper;
	      return createWrapper(wrapper, PARTIAL_FLAG, null, [value], []);
	    }

	    /*------------------------------------------------------------------------*/

	    /**
	     * Creates a clone of `value`. If `isDeep` is `true` nested objects are cloned,
	     * otherwise they are assigned by reference. If `customizer` is provided it is
	     * invoked to produce the cloned values. If `customizer` returns `undefined`
	     * cloning is handled by the method instead. The `customizer` is bound to
	     * `thisArg` and invoked with two argument; (value [, index|key, object]).
	     *
	     * **Note:** This method is loosely based on the structured clone algorithm.
	     * The enumerable properties of `arguments` objects and objects created by
	     * constructors other than `Object` are cloned to plain `Object` objects. An
	     * empty object is returned for uncloneable values such as functions, DOM nodes,
	     * Maps, Sets, and WeakMaps. See the [HTML5 specification](http://www.w3.org/TR/html5/infrastructure.html#internal-structured-cloning-algorithm)
	     * for more details.
	     *
	     * @static
	     * @memberOf _
	     * @category Lang
	     * @param {*} value The value to clone.
	     * @param {boolean} [isDeep] Specify a deep clone.
	     * @param {Function} [customizer] The function to customize cloning values.
	     * @param {*} [thisArg] The `this` binding of `customizer`.
	     * @returns {*} Returns the cloned value.
	     * @example
	     *
	     * var users = [
	     *   { 'user': 'barney' },
	     *   { 'user': 'fred' }
	     * ];
	     *
	     * var shallow = _.clone(users);
	     * shallow[0] === users[0];
	     * // => true
	     *
	     * var deep = _.clone(users, true);
	     * deep[0] === users[0];
	     * // => false
	     *
	     * // using a customizer callback
	     * var body = _.clone(document.body, function(value) {
	     *   return _.isElement(value) ? value.cloneNode(false) : undefined;
	     * });
	     *
	     * body === document.body
	     * // => false
	     * body.nodeName
	     * // => BODY
	     * body.childNodes.length;
	     * // => 0
	     */
	    function clone(value, isDeep, customizer, thisArg) {
	      // Juggle arguments.
	      if (typeof isDeep != 'boolean' && isDeep != null) {
	        thisArg = customizer;
	        customizer = isIterateeCall(value, isDeep, thisArg) ? null : isDeep;
	        isDeep = false;
	      }
	      customizer = typeof customizer == 'function' && bindCallback(customizer, thisArg, 1);
	      return baseClone(value, isDeep, customizer);
	    }

	    /**
	     * Creates a deep clone of `value`. If `customizer` is provided it is invoked
	     * to produce the cloned values. If `customizer` returns `undefined` cloning
	     * is handled by the method instead. The `customizer` is bound to `thisArg`
	     * and invoked with two argument; (value [, index|key, object]).
	     *
	     * **Note:** This method is loosely based on the structured clone algorithm.
	     * The enumerable properties of `arguments` objects and objects created by
	     * constructors other than `Object` are cloned to plain `Object` objects. An
	     * empty object is returned for uncloneable values such as functions, DOM nodes,
	     * Maps, Sets, and WeakMaps. See the [HTML5 specification](http://www.w3.org/TR/html5/infrastructure.html#internal-structured-cloning-algorithm)
	     * for more details.
	     *
	     * @static
	     * @memberOf _
	     * @category Lang
	     * @param {*} value The value to deep clone.
	     * @param {Function} [customizer] The function to customize cloning values.
	     * @param {*} [thisArg] The `this` binding of `customizer`.
	     * @returns {*} Returns the deep cloned value.
	     * @example
	     *
	     * var users = [
	     *   { 'user': 'barney' },
	     *   { 'user': 'fred' }
	     * ];
	     *
	     * var deep = _.cloneDeep(users);
	     * deep[0] === users[0];
	     * // => false
	     *
	     * // using a customizer callback
	     * var el = _.cloneDeep(document.body, function(value) {
	     *   return _.isElement(value) ? value.cloneNode(true) : undefined;
	     * });
	     *
	     * body === document.body
	     * // => false
	     * body.nodeName
	     * // => BODY
	     * body.childNodes.length;
	     * // => 20
	     */
	    function cloneDeep(value, customizer, thisArg) {
	      customizer = typeof customizer == 'function' && bindCallback(customizer, thisArg, 1);
	      return baseClone(value, true, customizer);
	    }

	    /**
	     * Checks if `value` is classified as an `arguments` object.
	     *
	     * @static
	     * @memberOf _
	     * @category Lang
	     * @param {*} value The value to check.
	     * @returns {boolean} Returns `true` if `value` is correctly classified, else `false`.
	     * @example
	     *
	     * (function() { return _.isArguments(arguments); })();
	     * // => true
	     *
	     * _.isArguments([1, 2, 3]);
	     * // => false
	     */
	    function isArguments(value) {
	      var length = isObjectLike(value) ? value.length : undefined;
	      return (isLength(length) && objToString.call(value) == argsTag) || false;
	    }

	    /**
	     * Checks if `value` is classified as an `Array` object.
	     *
	     * @static
	     * @memberOf _
	     * @category Lang
	     * @param {*} value The value to check.
	     * @returns {boolean} Returns `true` if `value` is correctly classified, else `false`.
	     * @example
	     *
	     * _.isArray([1, 2, 3]);
	     * // => true
	     *
	     * (function() { return _.isArray(arguments); })();
	     * // => false
	     */
	    var isArray = nativeIsArray || function(value) {
	      return (isObjectLike(value) && isLength(value.length) && objToString.call(value) == arrayTag) || false;
	    };

	    /**
	     * Checks if `value` is classified as a boolean primitive or object.
	     *
	     * @static
	     * @memberOf _
	     * @category Lang
	     * @param {*} value The value to check.
	     * @returns {boolean} Returns `true` if `value` is correctly classified, else `false`.
	     * @example
	     *
	     * _.isBoolean(false);
	     * // => true
	     *
	     * _.isBoolean(null);
	     * // => false
	     */
	    function isBoolean(value) {
	      return (value === true || value === false || isObjectLike(value) && objToString.call(value) == boolTag) || false;
	    }

	    /**
	     * Checks if `value` is classified as a `Date` object.
	     *
	     * @static
	     * @memberOf _
	     * @category Lang
	     * @param {*} value The value to check.
	     * @returns {boolean} Returns `true` if `value` is correctly classified, else `false`.
	     * @example
	     *
	     * _.isDate(new Date);
	     * // => true
	     *
	     * _.isDate('Mon April 23 2012');
	     * // => false
	     */
	    function isDate(value) {
	      return (isObjectLike(value) && objToString.call(value) == dateTag) || false;
	    }

	    /**
	     * Checks if `value` is a DOM element.
	     *
	     * @static
	     * @memberOf _
	     * @category Lang
	     * @param {*} value The value to check.
	     * @returns {boolean} Returns `true` if `value` is a DOM element, else `false`.
	     * @example
	     *
	     * _.isElement(document.body);
	     * // => true
	     *
	     * _.isElement('<body>');
	     * // => false
	     */
	    function isElement(value) {
	      return (value && value.nodeType === 1 && isObjectLike(value) &&
	        objToString.call(value).indexOf('Element') > -1) || false;
	    }
	    // Fallback for environments without DOM support.
	    if (!support.dom) {
	      isElement = function(value) {
	        return (value && value.nodeType === 1 && isObjectLike(value) && !isPlainObject(value)) || false;
	      };
	    }

	    /**
	     * Checks if a value is empty. A value is considered empty unless it is an
	     * `arguments` object, array, string, or jQuery-like collection with a length
	     * greater than `0` or an object with own enumerable properties.
	     *
	     * @static
	     * @memberOf _
	     * @category Lang
	     * @param {Array|Object|string} value The value to inspect.
	     * @returns {boolean} Returns `true` if `value` is empty, else `false`.
	     * @example
	     *
	     * _.isEmpty(null);
	     * // => true
	     *
	     * _.isEmpty(true);
	     * // => true
	     *
	     * _.isEmpty(1);
	     * // => true
	     *
	     * _.isEmpty([1, 2, 3]);
	     * // => false
	     *
	     * _.isEmpty({ 'a': 1 });
	     * // => false
	     */
	    function isEmpty(value) {
	      if (value == null) {
	        return true;
	      }
	      var length = value.length;
	      if (isLength(length) && (isArray(value) || isString(value) || isArguments(value) ||
	          (isObjectLike(value) && isFunction(value.splice)))) {
	        return !length;
	      }
	      return !keys(value).length;
	    }

	    /**
	     * Performs a deep comparison between two values to determine if they are
	     * equivalent. If `customizer` is provided it is invoked to compare values.
	     * If `customizer` returns `undefined` comparisons are handled by the method
	     * instead. The `customizer` is bound to `thisArg` and invoked with three
	     * arguments; (value, other [, index|key]).
	     *
	     * **Note:** This method supports comparing arrays, booleans, `Date` objects,
	     * numbers, `Object` objects, regexes, and strings. Functions and DOM nodes
	     * are **not** supported. Provide a customizer function to extend support
	     * for comparing other values.
	     *
	     * @static
	     * @memberOf _
	     * @category Lang
	     * @param {*} value The value to compare.
	     * @param {*} other The other value to compare.
	     * @param {Function} [customizer] The function to customize comparing values.
	     * @param {*} [thisArg] The `this` binding of `customizer`.
	     * @returns {boolean} Returns `true` if the values are equivalent, else `false`.
	     * @example
	     *
	     * var object = { 'user': 'fred' };
	     * var other = { 'user': 'fred' };
	     *
	     * object == other;
	     * // => false
	     *
	     * _.isEqual(object, other);
	     * // => true
	     *
	     * // using a customizer callback
	     * var array = ['hello', 'goodbye'];
	     * var other = ['hi', 'goodbye'];
	     *
	     * _.isEqual(array, other, function(value, other) {
	     *   return _.every([value, other], RegExp.prototype.test, /^h(?:i|ello)$/) || undefined;
	     * });
	     * // => true
	     */
	    function isEqual(value, other, customizer, thisArg) {
	      customizer = typeof customizer == 'function' && bindCallback(customizer, thisArg, 3);
	      if (!customizer && isStrictComparable(value) && isStrictComparable(other)) {
	        return value === other;
	      }
	      var result = customizer ? customizer(value, other) : undefined;
	      return typeof result == 'undefined' ? baseIsEqual(value, other, customizer) : !!result;
	    }

	    /**
	     * Checks if `value` is an `Error`, `EvalError`, `RangeError`, `ReferenceError`,
	     * `SyntaxError`, `TypeError`, or `URIError` object.
	     *
	     * @static
	     * @memberOf _
	     * @category Lang
	     * @param {*} value The value to check.
	     * @returns {boolean} Returns `true` if `value` is an error object, else `false`.
	     * @example
	     *
	     * _.isError(new Error);
	     * // => true
	     *
	     * _.isError(Error);
	     * // => false
	     */
	    function isError(value) {
	      return (isObjectLike(value) && typeof value.message == 'string' && objToString.call(value) == errorTag) || false;
	    }

	    /**
	     * Checks if `value` is a finite primitive number.
	     *
	     * **Note:** This method is based on ES `Number.isFinite`. See the
	     * [ES spec](https://people.mozilla.org/~jorendorff/es6-draft.html#sec-number.isfinite)
	     * for more details.
	     *
	     * @static
	     * @memberOf _
	     * @category Lang
	     * @param {*} value The value to check.
	     * @returns {boolean} Returns `true` if `value` is a finite number, else `false`.
	     * @example
	     *
	     * _.isFinite(10);
	     * // => true
	     *
	     * _.isFinite('10');
	     * // => false
	     *
	     * _.isFinite(true);
	     * // => false
	     *
	     * _.isFinite(Object(10));
	     * // => false
	     *
	     * _.isFinite(Infinity);
	     * // => false
	     */
	    var isFinite = nativeNumIsFinite || function(value) {
	      return typeof value == 'number' && nativeIsFinite(value);
	    };

	    /**
	     * Checks if `value` is classified as a `Function` object.
	     *
	     * @static
	     * @memberOf _
	     * @category Lang
	     * @param {*} value The value to check.
	     * @returns {boolean} Returns `true` if `value` is correctly classified, else `false`.
	     * @example
	     *
	     * _.isFunction(_);
	     * // => true
	     *
	     * _.isFunction(/abc/);
	     * // => false
	     */
	    function isFunction(value) {
	      // Avoid a Chakra JIT bug in compatibility modes of IE 11.
	      // See https://github.com/jashkenas/underscore/issues/1621 for more details.
	      return typeof value == 'function' || false;
	    }
	    // Fallback for environments that return incorrect `typeof` operator results.
	    if (isFunction(/x/) || (Uint8Array && !isFunction(Uint8Array))) {
	      isFunction = function(value) {
	        // The use of `Object#toString` avoids issues with the `typeof` operator
	        // in older versions of Chrome and Safari which return 'function' for regexes
	        // and Safari 8 equivalents which return 'object' for typed array constructors.
	        return objToString.call(value) == funcTag;
	      };
	    }

	    /**
	     * Checks if `value` is the language type of `Object`.
	     * (e.g. arrays, functions, objects, regexes, `new Number(0)`, and `new String('')`)
	     *
	     * **Note:** See the [ES5 spec](https://es5.github.io/#x8) for more details.
	     *
	     * @static
	     * @memberOf _
	     * @category Lang
	     * @param {*} value The value to check.
	     * @returns {boolean} Returns `true` if `value` is an object, else `false`.
	     * @example
	     *
	     * _.isObject({});
	     * // => true
	     *
	     * _.isObject([1, 2, 3]);
	     * // => true
	     *
	     * _.isObject(1);
	     * // => false
	     */
	    function isObject(value) {
	      // Avoid a V8 JIT bug in Chrome 19-20.
	      // See https://code.google.com/p/v8/issues/detail?id=2291 for more details.
	      var type = typeof value;
	      return type == 'function' || (value && type == 'object') || false;
	    }

	    /**
	     * Performs a deep comparison between `object` and `source` to determine if
	     * `object` contains equivalent property values. If `customizer` is provided
	     * it is invoked to compare values. If `customizer` returns `undefined`
	     * comparisons are handled by the method instead. The `customizer` is bound
	     * to `thisArg` and invoked with three arguments; (value, other, index|key).
	     *
	     * **Note:** This method supports comparing properties of arrays, booleans,
	     * `Date` objects, numbers, `Object` objects, regexes, and strings. Functions
	     * and DOM nodes are **not** supported. Provide a customizer function to extend
	     * support for comparing other values.
	     *
	     * @static
	     * @memberOf _
	     * @category Lang
	     * @param {Object} source The object to inspect.
	     * @param {Object} source The object of property values to match.
	     * @param {Function} [customizer] The function to customize comparing values.
	     * @param {*} [thisArg] The `this` binding of `customizer`.
	     * @returns {boolean} Returns `true` if `object` is a match, else `false`.
	     * @example
	     *
	     * var object = { 'user': 'fred', 'age': 40 };
	     *
	     * _.isMatch(object, { 'age': 40 });
	     * // => true
	     *
	     * _.isMatch(object, { 'age': 36 });
	     * // => false
	     *
	     * // using a customizer callback
	     * var object = { 'greeting': 'hello' };
	     * var source = { 'greeting': 'hi' };
	     *
	     * _.isMatch(object, source, function(value, other) {
	     *   return _.every([value, other], RegExp.prototype.test, /^h(?:i|ello)$/) || undefined;
	     * });
	     * // => true
	     */
	    function isMatch(object, source, customizer, thisArg) {
	      var props = keys(source),
	          length = props.length;

	      customizer = typeof customizer == 'function' && bindCallback(customizer, thisArg, 3);
	      if (!customizer && length == 1) {
	        var key = props[0],
	            value = source[key];

	        if (isStrictComparable(value)) {
	          return object != null && value === object[key] && hasOwnProperty.call(object, key);
	        }
	      }
	      var values = Array(length),
	          strictCompareFlags = Array(length);

	      while (length--) {
	        value = values[length] = source[props[length]];
	        strictCompareFlags[length] = isStrictComparable(value);
	      }
	      return baseIsMatch(object, props, values, strictCompareFlags, customizer);
	    }

	    /**
	     * Checks if `value` is `NaN`.
	     *
	     * **Note:** This method is not the same as native `isNaN` which returns `true`
	     * for `undefined` and other non-numeric values. See the [ES5 spec](https://es5.github.io/#x15.1.2.4)
	     * for more details.
	     *
	     * @static
	     * @memberOf _
	     * @category Lang
	     * @param {*} value The value to check.
	     * @returns {boolean} Returns `true` if `value` is `NaN`, else `false`.
	     * @example
	     *
	     * _.isNaN(NaN);
	     * // => true
	     *
	     * _.isNaN(new Number(NaN));
	     * // => true
	     *
	     * isNaN(undefined);
	     * // => true
	     *
	     * _.isNaN(undefined);
	     * // => false
	     */
	    function isNaN(value) {
	      // An `NaN` primitive is the only value that is not equal to itself.
	      // Perform the `toStringTag` check first to avoid errors with some host objects in IE.
	      return isNumber(value) && value != +value;
	    }

	    /**
	     * Checks if `value` is a native function.
	     *
	     * @static
	     * @memberOf _
	     * @category Lang
	     * @param {*} value The value to check.
	     * @returns {boolean} Returns `true` if `value` is a native function, else `false`.
	     * @example
	     *
	     * _.isNative(Array.prototype.push);
	     * // => true
	     *
	     * _.isNative(_);
	     * // => false
	     */
	    function isNative(value) {
	      if (value == null) {
	        return false;
	      }
	      if (objToString.call(value) == funcTag) {
	        return reNative.test(fnToString.call(value));
	      }
	      return (isObjectLike(value) && reHostCtor.test(value)) || false;
	    }

	    /**
	     * Checks if `value` is `null`.
	     *
	     * @static
	     * @memberOf _
	     * @category Lang
	     * @param {*} value The value to check.
	     * @returns {boolean} Returns `true` if `value` is `null`, else `false`.
	     * @example
	     *
	     * _.isNull(null);
	     * // => true
	     *
	     * _.isNull(void 0);
	     * // => false
	     */
	    function isNull(value) {
	      return value === null;
	    }

	    /**
	     * Checks if `value` is classified as a `Number` primitive or object.
	     *
	     * **Note:** To exclude `Infinity`, `-Infinity`, and `NaN`, which are classified
	     * as numbers, use the `_.isFinite` method.
	     *
	     * @static
	     * @memberOf _
	     * @category Lang
	     * @param {*} value The value to check.
	     * @returns {boolean} Returns `true` if `value` is correctly classified, else `false`.
	     * @example
	     *
	     * _.isNumber(8.4);
	     * // => true
	     *
	     * _.isNumber(NaN);
	     * // => true
	     *
	     * _.isNumber('8.4');
	     * // => false
	     */
	    function isNumber(value) {
	      return typeof value == 'number' || (isObjectLike(value) && objToString.call(value) == numberTag) || false;
	    }

	    /**
	     * Checks if `value` is a plain object, that is, an object created by the
	     * `Object` constructor or one with a `[[Prototype]]` of `null`.
	     *
	     * **Note:** This method assumes objects created by the `Object` constructor
	     * have no inherited enumerable properties.
	     *
	     * @static
	     * @memberOf _
	     * @category Lang
	     * @param {*} value The value to check.
	     * @returns {boolean} Returns `true` if `value` is a plain object, else `false`.
	     * @example
	     *
	     * function Foo() {
	     *   this.a = 1;
	     * }
	     *
	     * _.isPlainObject(new Foo);
	     * // => false
	     *
	     * _.isPlainObject([1, 2, 3]);
	     * // => false
	     *
	     * _.isPlainObject({ 'x': 0, 'y': 0 });
	     * // => true
	     *
	     * _.isPlainObject(Object.create(null));
	     * // => true
	     */
	    var isPlainObject = !getPrototypeOf ? shimIsPlainObject : function(value) {
	      if (!(value && objToString.call(value) == objectTag)) {
	        return false;
	      }
	      var valueOf = value.valueOf,
	          objProto = isNative(valueOf) && (objProto = getPrototypeOf(valueOf)) && getPrototypeOf(objProto);

	      return objProto
	        ? (value == objProto || getPrototypeOf(value) == objProto)
	        : shimIsPlainObject(value);
	    };

	    /**
	     * Checks if `value` is classified as a `RegExp` object.
	     *
	     * @static
	     * @memberOf _
	     * @category Lang
	     * @param {*} value The value to check.
	     * @returns {boolean} Returns `true` if `value` is correctly classified, else `false`.
	     * @example
	     *
	     * _.isRegExp(/abc/);
	     * // => true
	     *
	     * _.isRegExp('/abc/');
	     * // => false
	     */
	    function isRegExp(value) {
	      return (isObjectLike(value) && objToString.call(value) == regexpTag) || false;
	    }

	    /**
	     * Checks if `value` is classified as a `String` primitive or object.
	     *
	     * @static
	     * @memberOf _
	     * @category Lang
	     * @param {*} value The value to check.
	     * @returns {boolean} Returns `true` if `value` is correctly classified, else `false`.
	     * @example
	     *
	     * _.isString('abc');
	     * // => true
	     *
	     * _.isString(1);
	     * // => false
	     */
	    function isString(value) {
	      return typeof value == 'string' || (isObjectLike(value) && objToString.call(value) == stringTag) || false;
	    }

	    /**
	     * Checks if `value` is classified as a typed array.
	     *
	     * @static
	     * @memberOf _
	     * @category Lang
	     * @param {*} value The value to check.
	     * @returns {boolean} Returns `true` if `value` is correctly classified, else `false`.
	     * @example
	     *
	     * _.isTypedArray(new Uint8Array);
	     * // => true
	     *
	     * _.isTypedArray([]);
	     * // => false
	     */
	    function isTypedArray(value) {
	      return (isObjectLike(value) && isLength(value.length) && typedArrayTags[objToString.call(value)]) || false;
	    }

	    /**
	     * Checks if `value` is `undefined`.
	     *
	     * @static
	     * @memberOf _
	     * @category Lang
	     * @param {*} value The value to check.
	     * @returns {boolean} Returns `true` if `value` is `undefined`, else `false`.
	     * @example
	     *
	     * _.isUndefined(void 0);
	     * // => true
	     *
	     * _.isUndefined(null);
	     * // => false
	     */
	    function isUndefined(value) {
	      return typeof value == 'undefined';
	    }

	    /**
	     * Converts `value` to an array.
	     *
	     * @static
	     * @memberOf _
	     * @category Lang
	     * @param {*} value The value to convert.
	     * @returns {Array} Returns the converted array.
	     * @example
	     *
	     * (function() { return _.toArray(arguments).slice(1); })(1, 2, 3);
	     * // => [2, 3]
	     */
	    function toArray(value) {
	      var length = value ? value.length : 0;
	      if (!isLength(length)) {
	        return values(value);
	      }
	      if (!length) {
	        return [];
	      }
	      return arrayCopy(value);
	    }

	    /**
	     * Converts `value` to a plain object flattening inherited enumerable
	     * properties of `value` to own properties of the plain object.
	     *
	     * @static
	     * @memberOf _
	     * @category Lang
	     * @param {*} value The value to convert.
	     * @returns {Object} Returns the converted plain object.
	     * @example
	     *
	     * function Foo() {
	     *   this.b = 2;
	     * }
	     *
	     * Foo.prototype.c = 3;
	     *
	     * _.assign({ 'a': 1 }, new Foo);
	     * // => { 'a': 1, 'b': 2 }
	     *
	     * _.assign({ 'a': 1 }, _.toPlainObject(new Foo));
	     * // => { 'a': 1, 'b': 2, 'c': 3 }
	     */
	    function toPlainObject(value) {
	      return baseCopy(value, keysIn(value));
	    }

	    /*------------------------------------------------------------------------*/

	    /**
	     * Assigns own enumerable properties of source object(s) to the destination
	     * object. Subsequent sources overwrite property assignments of previous sources.
	     * If `customizer` is provided it is invoked to produce the assigned values.
	     * The `customizer` is bound to `thisArg` and invoked with five arguments;
	     * (objectValue, sourceValue, key, object, source).
	     *
	     * @static
	     * @memberOf _
	     * @alias extend
	     * @category Object
	     * @param {Object} object The destination object.
	     * @param {...Object} [sources] The source objects.
	     * @param {Function} [customizer] The function to customize assigning values.
	     * @param {*} [thisArg] The `this` binding of `customizer`.
	     * @returns {Object} Returns `object`.
	     * @example
	     *
	     * _.assign({ 'user': 'barney' }, { 'age': 40 }, { 'user': 'fred' });
	     * // => { 'user': 'fred', 'age': 40 }
	     *
	     * // using a customizer callback
	     * var defaults = _.partialRight(_.assign, function(value, other) {
	     *   return typeof value == 'undefined' ? other : value;
	     * });
	     *
	     * defaults({ 'user': 'barney' }, { 'age': 36 }, { 'user': 'fred' });
	     * // => { 'user': 'barney', 'age': 36 }
	     */
	    var assign = createAssigner(baseAssign);

	    /**
	     * Creates an object that inherits from the given `prototype` object. If a
	     * `properties` object is provided its own enumerable properties are assigned
	     * to the created object.
	     *
	     * @static
	     * @memberOf _
	     * @category Object
	     * @param {Object} prototype The object to inherit from.
	     * @param {Object} [properties] The properties to assign to the object.
	     * @param- {Object} [guard] Enables use as a callback for functions like `_.map`.
	     * @returns {Object} Returns the new object.
	     * @example
	     *
	     * function Shape() {
	     *   this.x = 0;
	     *   this.y = 0;
	     * }
	     *
	     * function Circle() {
	     *   Shape.call(this);
	     * }
	     *
	     * Circle.prototype = _.create(Shape.prototype, { 'constructor': Circle });
	     *
	     * var circle = new Circle;
	     * circle instanceof Circle;
	     * // => true
	     *
	     * circle instanceof Shape;
	     * // => true
	     */
	    function create(prototype, properties, guard) {
	      var result = baseCreate(prototype);
	      if (guard && isIterateeCall(prototype, properties, guard)) {
	        properties = null;
	      }
	      return properties ? baseCopy(properties, result, keys(properties)) : result;
	    }

	    /**
	     * Assigns own enumerable properties of source object(s) to the destination
	     * object for all destination properties that resolve to `undefined`. Once a
	     * property is set, additional defaults of the same property are ignored.
	     *
	     * @static
	     * @memberOf _
	     * @category Object
	     * @param {Object} object The destination object.
	     * @param {...Object} [sources] The source objects.
	     * @returns {Object} Returns `object`.
	     * @example
	     *
	     * _.defaults({ 'user': 'barney' }, { 'age': 36 }, { 'user': 'fred' });
	     * // => { 'user': 'barney', 'age': 36 }
	     */
	    function defaults(object) {
	      if (object == null) {
	        return object;
	      }
	      var args = arrayCopy(arguments);
	      args.push(assignDefaults);
	      return assign.apply(undefined, args);
	    }

	    /**
	     * This method is like `_.findIndex` except that it returns the key of the
	     * first element `predicate` returns truthy for, instead of the element itself.
	     *
	     * If a property name is provided for `predicate` the created "_.property"
	     * style callback returns the property value of the given element.
	     *
	     * If an object is provided for `predicate` the created "_.matches" style
	     * callback returns `true` for elements that have the properties of the given
	     * object, else `false`.
	     *
	     * @static
	     * @memberOf _
	     * @category Object
	     * @param {Object} object The object to search.
	     * @param {Function|Object|string} [predicate=_.identity] The function invoked
	     *  per iteration. If a property name or object is provided it is used to
	     *  create a "_.property" or "_.matches" style callback respectively.
	     * @param {*} [thisArg] The `this` binding of `predicate`.
	     * @returns {string|undefined} Returns the key of the matched element, else `undefined`.
	     * @example
	     *
	     * var users = {
	     *   'barney':  { 'age': 36, 'active': true },
	     *   'fred':    { 'age': 40, 'active': false },
	     *   'pebbles': { 'age': 1,  'active': true }
	     * };
	     *
	     * _.findKey(users, function(chr) { return chr.age < 40; });
	     * // => 'barney' (iteration order is not guaranteed)
	     *
	     * // using the "_.matches" callback shorthand
	     * _.findKey(users, { 'age': 1 });
	     * // => 'pebbles'
	     *
	     * // using the "_.property" callback shorthand
	     * _.findKey(users, 'active');
	     * // => 'barney'
	     */
	    function findKey(object, predicate, thisArg) {
	      predicate = getCallback(predicate, thisArg, 3);
	      return baseFind(object, predicate, baseForOwn, true);
	    }

	    /**
	     * This method is like `_.findKey` except that it iterates over elements of
	     * a collection in the opposite order.
	     *
	     * If a property name is provided for `predicate` the created "_.property"
	     * style callback returns the property value of the given element.
	     *
	     * If an object is provided for `predicate` the created "_.matches" style
	     * callback returns `true` for elements that have the properties of the given
	     * object, else `false`.
	     *
	     * @static
	     * @memberOf _
	     * @category Object
	     * @param {Object} object The object to search.
	     * @param {Function|Object|string} [predicate=_.identity] The function invoked
	     *  per iteration. If a property name or object is provided it is used to
	     *  create a "_.property" or "_.matches" style callback respectively.
	     * @param {*} [thisArg] The `this` binding of `predicate`.
	     * @returns {string|undefined} Returns the key of the matched element, else `undefined`.
	     * @example
	     *
	     * var users = {
	     *   'barney':  { 'age': 36, 'active': true },
	     *   'fred':    { 'age': 40, 'active': false },
	     *   'pebbles': { 'age': 1,  'active': true }
	     * };
	     *
	     * _.findLastKey(users, function(chr) { return chr.age < 40; });
	     * // => returns `pebbles` assuming `_.findKey` returns `barney`
	     *
	     * // using the "_.matches" callback shorthand
	     * _.findLastKey(users, { 'age': 36 });
	     * // => 'barney'
	     *
	     * // using the "_.property" callback shorthand
	     * _.findLastKey(users, 'active');
	     * // => 'pebbles'
	     */
	    function findLastKey(object, predicate, thisArg) {
	      predicate = getCallback(predicate, thisArg, 3);
	      return baseFind(object, predicate, baseForOwnRight, true);
	    }

	    /**
	     * Iterates over own and inherited enumerable properties of an object invoking
	     * `iteratee` for each property. The `iteratee` is bound to `thisArg` and invoked
	     * with three arguments; (value, key, object). Iterator functions may exit
	     * iteration early by explicitly returning `false`.
	     *
	     * @static
	     * @memberOf _
	     * @category Object
	     * @param {Object} object The object to iterate over.
	     * @param {Function} [iteratee=_.identity] The function invoked per iteration.
	     * @param {*} [thisArg] The `this` binding of `iteratee`.
	     * @returns {Object} Returns `object`.
	     * @example
	     *
	     * function Foo() {
	     *   this.a = 1;
	     *   this.b = 2;
	     * }
	     *
	     * Foo.prototype.c = 3;
	     *
	     * _.forIn(new Foo, function(value, key) {
	     *   console.log(key);
	     * });
	     * // => logs 'a', 'b', and 'c' (iteration order is not guaranteed)
	     */
	    function forIn(object, iteratee, thisArg) {
	      if (typeof iteratee != 'function' || typeof thisArg != 'undefined') {
	        iteratee = bindCallback(iteratee, thisArg, 3);
	      }
	      return baseFor(object, iteratee, keysIn);
	    }

	    /**
	     * This method is like `_.forIn` except that it iterates over properties of
	     * `object` in the opposite order.
	     *
	     * @static
	     * @memberOf _
	     * @category Object
	     * @param {Object} object The object to iterate over.
	     * @param {Function} [iteratee=_.identity] The function invoked per iteration.
	     * @param {*} [thisArg] The `this` binding of `iteratee`.
	     * @returns {Object} Returns `object`.
	     * @example
	     *
	     * function Foo() {
	     *   this.a = 1;
	     *   this.b = 2;
	     * }
	     *
	     * Foo.prototype.c = 3;
	     *
	     * _.forInRight(new Foo, function(value, key) {
	     *   console.log(key);
	     * });
	     * // => logs 'c', 'b', and 'a' assuming `_.forIn ` logs 'a', 'b', and 'c'
	     */
	    function forInRight(object, iteratee, thisArg) {
	      iteratee = bindCallback(iteratee, thisArg, 3);
	      return baseForRight(object, iteratee, keysIn);
	    }

	    /**
	     * Iterates over own enumerable properties of an object invoking `iteratee`
	     * for each property. The `iteratee` is bound to `thisArg` and invoked with
	     * three arguments; (value, key, object). Iterator functions may exit iteration
	     * early by explicitly returning `false`.
	     *
	     * @static
	     * @memberOf _
	     * @category Object
	     * @param {Object} object The object to iterate over.
	     * @param {Function} [iteratee=_.identity] The function invoked per iteration.
	     * @param {*} [thisArg] The `this` binding of `iteratee`.
	     * @returns {Object} Returns `object`.
	     * @example
	     *
	     * _.forOwn({ '0': 'zero', '1': 'one', 'length': 2 }, function(n, key) {
	     *   console.log(key);
	     * });
	     * // => logs '0', '1', and 'length' (iteration order is not guaranteed)
	     */
	    function forOwn(object, iteratee, thisArg) {
	      if (typeof iteratee != 'function' || typeof thisArg != 'undefined') {
	        iteratee = bindCallback(iteratee, thisArg, 3);
	      }
	      return baseForOwn(object, iteratee);
	    }

	    /**
	     * This method is like `_.forOwn` except that it iterates over properties of
	     * `object` in the opposite order.
	     *
	     * @static
	     * @memberOf _
	     * @category Object
	     * @param {Object} object The object to iterate over.
	     * @param {Function} [iteratee=_.identity] The function invoked per iteration.
	     * @param {*} [thisArg] The `this` binding of `iteratee`.
	     * @returns {Object} Returns `object`.
	     * @example
	     *
	     * _.forOwnRight({ '0': 'zero', '1': 'one', 'length': 2 }, function(n, key) {
	     *   console.log(key);
	     * });
	     * // => logs 'length', '1', and '0' assuming `_.forOwn` logs '0', '1', and 'length'
	     */
	    function forOwnRight(object, iteratee, thisArg) {
	      iteratee = bindCallback(iteratee, thisArg, 3);
	      return baseForRight(object, iteratee, keys);
	    }

	    /**
	     * Creates an array of function property names from all enumerable properties,
	     * own and inherited, of `object`.
	     *
	     * @static
	     * @memberOf _
	     * @alias methods
	     * @category Object
	     * @param {Object} object The object to inspect.
	     * @returns {Array} Returns the new array of property names.
	     * @example
	     *
	     * _.functions(_);
	     * // => ['all', 'any', 'bind', ...]
	     */
	    function functions(object) {
	      return baseFunctions(object, keysIn(object));
	    }

	    /**
	     * Checks if `key` exists as a direct property of `object` instead of an
	     * inherited property.
	     *
	     * @static
	     * @memberOf _
	     * @category Object
	     * @param {Object} object The object to inspect.
	     * @param {string} key The key to check.
	     * @returns {boolean} Returns `true` if `key` is a direct property, else `false`.
	     * @example
	     *
	     * _.has({ 'a': 1, 'b': 2, 'c': 3 }, 'b');
	     * // => true
	     */
	    function has(object, key) {
	      return object ? hasOwnProperty.call(object, key) : false;
	    }

	    /**
	     * Creates an object composed of the inverted keys and values of `object`.
	     * If `object` contains duplicate values, subsequent values overwrite property
	     * assignments of previous values unless `multiValue` is `true`.
	     *
	     * @static
	     * @memberOf _
	     * @category Object
	     * @param {Object} object The object to invert.
	     * @param {boolean} [multiValue] Allow multiple values per key.
	     * @param- {Object} [guard] Enables use as a callback for functions like `_.map`.
	     * @returns {Object} Returns the new inverted object.
	     * @example
	     *
	     * _.invert({ 'first': 'fred', 'second': 'barney' });
	     * // => { 'fred': 'first', 'barney': 'second' }
	     *
	     * // without `multiValue`
	     * _.invert({ 'first': 'fred', 'second': 'barney', 'third': 'fred' });
	     * // => { 'fred': 'third', 'barney': 'second' }
	     *
	     * // with `multiValue`
	     * _.invert({ 'first': 'fred', 'second': 'barney', 'third': 'fred' }, true);
	     * // => { 'fred': ['first', 'third'], 'barney': ['second'] }
	     */
	    function invert(object, multiValue, guard) {
	      if (guard && isIterateeCall(object, multiValue, guard)) {
	        multiValue = null;
	      }
	      var index = -1,
	          props = keys(object),
	          length = props.length,
	          result = {};

	      while (++index < length) {
	        var key = props[index],
	            value = object[key];

	        if (multiValue) {
	          if (hasOwnProperty.call(result, value)) {
	            result[value].push(key);
	          } else {
	            result[value] = [key];
	          }
	        }
	        else {
	          result[value] = key;
	        }
	      }
	      return result;
	    }

	    /**
	     * Creates an array of the own enumerable property names of `object`.
	     *
	     * **Note:** Non-object values are coerced to objects. See the
	     * [ES spec](https://people.mozilla.org/~jorendorff/es6-draft.html#sec-object.keys)
	     * for more details.
	     *
	     * @static
	     * @memberOf _
	     * @category Object
	     * @param {Object} object The object to inspect.
	     * @returns {Array} Returns the array of property names.
	     * @example
	     *
	     * function Foo() {
	     *   this.a = 1;
	     *   this.b = 2;
	     * }
	     *
	     * Foo.prototype.c = 3;
	     *
	     * _.keys(new Foo);
	     * // => ['a', 'b'] (iteration order is not guaranteed)
	     *
	     * _.keys('hi');
	     * // => ['0', '1']
	     */
	    var keys = !nativeKeys ? shimKeys : function(object) {
	      if (object) {
	        var Ctor = object.constructor,
	            length = object.length;
	      }
	      if ((typeof Ctor == 'function' && Ctor.prototype === object) ||
	         (typeof object != 'function' && (length && isLength(length)))) {
	        return shimKeys(object);
	      }
	      return isObject(object) ? nativeKeys(object) : [];
	    };

	    /**
	     * Creates an array of the own and inherited enumerable property names of `object`.
	     *
	     * **Note:** Non-object values are coerced to objects.
	     *
	     * @static
	     * @memberOf _
	     * @category Object
	     * @param {Object} object The object to inspect.
	     * @returns {Array} Returns the array of property names.
	     * @example
	     *
	     * function Foo() {
	     *   this.a = 1;
	     *   this.b = 2;
	     * }
	     *
	     * Foo.prototype.c = 3;
	     *
	     * _.keysIn(new Foo);
	     * // => ['a', 'b', 'c'] (iteration order is not guaranteed)
	     */
	    function keysIn(object) {
	      if (object == null) {
	        return [];
	      }
	      if (!isObject(object)) {
	        object = Object(object);
	      }
	      var length = object.length;
	      length = (length && isLength(length) &&
	        (isArray(object) || (support.nonEnumArgs && isArguments(object))) && length) || 0;

	      var Ctor = object.constructor,
	          index = -1,
	          isProto = typeof Ctor == 'function' && Ctor.prototype == object,
	          result = Array(length),
	          skipIndexes = length > 0;

	      while (++index < length) {
	        result[index] = (index + '');
	      }
	      for (var key in object) {
	        if (!(skipIndexes && isIndex(key, length)) &&
	            !(key == 'constructor' && (isProto || !hasOwnProperty.call(object, key)))) {
	          result.push(key);
	        }
	      }
	      return result;
	    }

	    /**
	     * Creates an object with the same keys as `object` and values generated by
	     * running each own enumerable property of `object` through `iteratee`. The
	     * iteratee function is bound to `thisArg` and invoked with three arguments;
	     * (value, key, object).
	     *
	     * If a property name is provided for `iteratee` the created "_.property"
	     * style callback returns the property value of the given element.
	     *
	     * If an object is provided for `iteratee` the created "_.matches" style
	     * callback returns `true` for elements that have the properties of the given
	     * object, else `false`.
	     *
	     * @static
	     * @memberOf _
	     * @category Object
	     * @param {Object} object The object to iterate over.
	     * @param {Function|Object|string} [iteratee=_.identity] The function invoked
	     *  per iteration. If a property name or object is provided it is used to
	     *  create a "_.property" or "_.matches" style callback respectively.
	     * @param {*} [thisArg] The `this` binding of `iteratee`.
	     * @returns {Object} Returns the new mapped object.
	     * @example
	     *
	     * _.mapValues({ 'a': 1, 'b': 2, 'c': 3} , function(n) { return n * 3; });
	     * // => { 'a': 3, 'b': 6, 'c': 9 }
	     *
	     * var users = {
	     *   'fred':    { 'user': 'fred',    'age': 40 },
	     *   'pebbles': { 'user': 'pebbles', 'age': 1 }
	     * };
	     *
	     * // using the "_.property" callback shorthand
	     * _.mapValues(users, 'age');
	     * // => { 'fred': 40, 'pebbles': 1 } (iteration order is not guaranteed)
	     */
	    function mapValues(object, iteratee, thisArg) {
	      var result = {};
	      iteratee = getCallback(iteratee, thisArg, 3);

	      baseForOwn(object, function(value, key, object) {
	        result[key] = iteratee(value, key, object);
	      });
	      return result;
	    }

	    /**
	     * Recursively merges own enumerable properties of the source object(s), that
	     * don't resolve to `undefined` into the destination object. Subsequent sources
	     * overwrite property assignments of previous sources. If `customizer` is
	     * provided it is invoked to produce the merged values of the destination and
	     * source properties. If `customizer` returns `undefined` merging is handled
	     * by the method instead. The `customizer` is bound to `thisArg` and invoked
	     * with five arguments; (objectValue, sourceValue, key, object, source).
	     *
	     * @static
	     * @memberOf _
	     * @category Object
	     * @param {Object} object The destination object.
	     * @param {...Object} [sources] The source objects.
	     * @param {Function} [customizer] The function to customize merging properties.
	     * @param {*} [thisArg] The `this` binding of `customizer`.
	     * @returns {Object} Returns `object`.
	     * @example
	     *
	     * var users = {
	     *   'data': [{ 'user': 'barney' }, { 'user': 'fred' }]
	     * };
	     *
	     * var ages = {
	     *   'data': [{ 'age': 36 }, { 'age': 40 }]
	     * };
	     *
	     * _.merge(users, ages);
	     * // => { 'data': [{ 'user': 'barney', 'age': 36 }, { 'user': 'fred', 'age': 40 }] }
	     *
	     * // using a customizer callback
	     * var object = {
	     *   'fruits': ['apple'],
	     *   'vegetables': ['beet']
	     * };
	     *
	     * var other = {
	     *   'fruits': ['banana'],
	     *   'vegetables': ['carrot']
	     * };
	     *
	     * _.merge(object, other, function(a, b) {
	     *   return _.isArray(a) ? a.concat(b) : undefined;
	     * });
	     * // => { 'fruits': ['apple', 'banana'], 'vegetables': ['beet', 'carrot'] }
	     */
	    var merge = createAssigner(baseMerge);

	    /**
	     * The opposite of `_.pick`; this method creates an object composed of the
	     * own and inherited enumerable properties of `object` that are not omitted.
	     * Property names may be specified as individual arguments or as arrays of
	     * property names. If `predicate` is provided it is invoked for each property
	     * of `object` omitting the properties `predicate` returns truthy for. The
	     * predicate is bound to `thisArg` and invoked with three arguments;
	     * (value, key, object).
	     *
	     * @static
	     * @memberOf _
	     * @category Object
	     * @param {Object} object The source object.
	     * @param {Function|...(string|string[])} [predicate] The function invoked per
	     *  iteration or property names to omit, specified as individual property
	     *  names or arrays of property names.
	     * @param {*} [thisArg] The `this` binding of `predicate`.
	     * @returns {Object} Returns the new object.
	     * @example
	     *
	     * var object = { 'user': 'fred', 'age': 40 };
	     *
	     * _.omit(object, 'age');
	     * // => { 'user': 'fred' }
	     *
	     * _.omit(object, _.isNumber);
	     * // => { 'user': 'fred' }
	     */
	    function omit(object, predicate, thisArg) {
	      if (object == null) {
	        return {};
	      }
	      if (typeof predicate != 'function') {
	        var props = arrayMap(baseFlatten(arguments, false, false, 1), String);
	        return pickByArray(object, baseDifference(keysIn(object), props));
	      }
	      predicate = bindCallback(predicate, thisArg, 3);
	      return pickByCallback(object, function(value, key, object) {
	        return !predicate(value, key, object);
	      });
	    }

	    /**
	     * Creates a two dimensional array of the key-value pairs for `object`,
	     * e.g. `[[key1, value1], [key2, value2]]`.
	     *
	     * @static
	     * @memberOf _
	     * @category Object
	     * @param {Object} object The object to inspect.
	     * @returns {Array} Returns the new array of key-value pairs.
	     * @example
	     *
	     * _.pairs({ 'barney': 36, 'fred': 40 });
	     * // => [['barney', 36], ['fred', 40]] (iteration order is not guaranteed)
	     */
	    function pairs(object) {
	      var index = -1,
	          props = keys(object),
	          length = props.length,
	          result = Array(length);

	      while (++index < length) {
	        var key = props[index];
	        result[index] = [key, object[key]];
	      }
	      return result;
	    }

	    /**
	     * Creates an object composed of the picked `object` properties. Property
	     * names may be specified as individual arguments or as arrays of property
	     * names. If `predicate` is provided it is invoked for each property of `object`
	     * picking the properties `predicate` returns truthy for. The predicate is
	     * bound to `thisArg` and invoked with three arguments; (value, key, object).
	     *
	     * @static
	     * @memberOf _
	     * @category Object
	     * @param {Object} object The source object.
	     * @param {Function|...(string|string[])} [predicate] The function invoked per
	     *  iteration or property names to pick, specified as individual property
	     *  names or arrays of property names.
	     * @param {*} [thisArg] The `this` binding of `predicate`.
	     * @returns {Object} Returns the new object.
	     * @example
	     *
	     * var object = { 'user': 'fred', 'age': 40 };
	     *
	     * _.pick(object, 'user');
	     * // => { 'user': 'fred' }
	     *
	     * _.pick(object, _.isString);
	     * // => { 'user': 'fred' }
	     */
	    function pick(object, predicate, thisArg) {
	      if (object == null) {
	        return {};
	      }
	      return typeof predicate == 'function'
	        ? pickByCallback(object, bindCallback(predicate, thisArg, 3))
	        : pickByArray(object, baseFlatten(arguments, false, false, 1));
	    }

	    /**
	     * Resolves the value of property `key` on `object`. If the value of `key` is
	     * a function it is invoked with the `this` binding of `object` and its result
	     * is returned, else the property value is returned. If the property value is
	     * `undefined` the `defaultValue` is used in its place.
	     *
	     * @static
	     * @memberOf _
	     * @category Object
	     * @param {Object} object The object to query.
	     * @param {string} key The key of the property to resolve.
	     * @param {*} [defaultValue] The value returned if the property value
	     *  resolves to `undefined`.
	     * @returns {*} Returns the resolved value.
	     * @example
	     *
	     * var object = { 'user': 'fred', 'age': _.constant(40) };
	     *
	     * _.result(object, 'user');
	     * // => 'fred'
	     *
	     * _.result(object, 'age');
	     * // => 40
	     *
	     * _.result(object, 'status', 'busy');
	     * // => 'busy'
	     *
	     * _.result(object, 'status', _.constant('busy'));
	     * // => 'busy'
	     */
	    function result(object, key, defaultValue) {
	      var value = object == null ? undefined : object[key];
	      if (typeof value == 'undefined') {
	        value = defaultValue;
	      }
	      return isFunction(value) ? value.call(object) : value;
	    }

	    /**
	     * An alternative to `_.reduce`; this method transforms `object` to a new
	     * `accumulator` object which is the result of running each of its own enumerable
	     * properties through `iteratee`, with each invocation potentially mutating
	     * the `accumulator` object. The `iteratee` is bound to `thisArg` and invoked
	     * with four arguments; (accumulator, value, key, object). Iterator functions
	     * may exit iteration early by explicitly returning `false`.
	     *
	     * @static
	     * @memberOf _
	     * @category Object
	     * @param {Array|Object} object The object to iterate over.
	     * @param {Function} [iteratee=_.identity] The function invoked per iteration.
	     * @param {*} [accumulator] The custom accumulator value.
	     * @param {*} [thisArg] The `this` binding of `iteratee`.
	     * @returns {*} Returns the accumulated value.
	     * @example
	     *
	     * var squares = _.transform([1, 2, 3, 4, 5, 6], function(result, n) {
	     *   n *= n;
	     *   if (n % 2) {
	     *     return result.push(n) < 3;
	     *   }
	     * });
	     * // => [1, 9, 25]
	     *
	     * var mapped = _.transform({ 'a': 1, 'b': 2, 'c': 3 }, function(result, n, key) {
	     *   result[key] = n * 3;
	     * });
	     * // => { 'a': 3, 'b': 6, 'c': 9 }
	     */
	    function transform(object, iteratee, accumulator, thisArg) {
	      var isArr = isArray(object) || isTypedArray(object);
	      iteratee = getCallback(iteratee, thisArg, 4);

	      if (accumulator == null) {
	        if (isArr || isObject(object)) {
	          var Ctor = object.constructor;
	          if (isArr) {
	            accumulator = isArray(object) ? new Ctor : [];
	          } else {
	            accumulator = baseCreate(typeof Ctor == 'function' && Ctor.prototype);
	          }
	        } else {
	          accumulator = {};
	        }
	      }
	      (isArr ? arrayEach : baseForOwn)(object, function(value, index, object) {
	        return iteratee(accumulator, value, index, object);
	      });
	      return accumulator;
	    }

	    /**
	     * Creates an array of the own enumerable property values of `object`.
	     *
	     * **Note:** Non-object values are coerced to objects.
	     *
	     * @static
	     * @memberOf _
	     * @category Object
	     * @param {Object} object The object to query.
	     * @returns {Array} Returns the array of property values.
	     * @example
	     *
	     * function Foo() {
	     *   this.a = 1;
	     *   this.b = 2;
	     * }
	     *
	     * Foo.prototype.c = 3;
	     *
	     * _.values(new Foo);
	     * // => [1, 2] (iteration order is not guaranteed)
	     *
	     * _.values('hi');
	     * // => ['h', 'i']
	     */
	    function values(object) {
	      return baseValues(object, keys(object));
	    }

	    /**
	     * Creates an array of the own and inherited enumerable property values
	     * of `object`.
	     *
	     * **Note:** Non-object values are coerced to objects.
	     *
	     * @static
	     * @memberOf _
	     * @category Object
	     * @param {Object} object The object to query.
	     * @returns {Array} Returns the array of property values.
	     * @example
	     *
	     * function Foo() {
	     *   this.a = 1;
	     *   this.b = 2;
	     * }
	     *
	     * Foo.prototype.c = 3;
	     *
	     * _.valuesIn(new Foo);
	     * // => [1, 2, 3] (iteration order is not guaranteed)
	     */
	    function valuesIn(object) {
	      return baseValues(object, keysIn(object));
	    }

	    /*------------------------------------------------------------------------*/

	    /**
	     * Produces a random number between `min` and `max` (inclusive). If only one
	     * argument is provided a number between `0` and the given number is returned.
	     * If `floating` is `true`, or either `min` or `max` are floats, a floating-point
	     * number is returned instead of an integer.
	     *
	     * @static
	     * @memberOf _
	     * @category Number
	     * @param {number} [min=0] The minimum possible value.
	     * @param {number} [max=1] The maximum possible value.
	     * @param {boolean} [floating] Specify returning a floating-point number.
	     * @returns {number} Returns the random number.
	     * @example
	     *
	     * _.random(0, 5);
	     * // => an integer between 0 and 5
	     *
	     * _.random(5);
	     * // => also an integer between 0 and 5
	     *
	     * _.random(5, true);
	     * // => a floating-point number between 0 and 5
	     *
	     * _.random(1.2, 5.2);
	     * // => a floating-point number between 1.2 and 5.2
	     */
	    function random(min, max, floating) {
	      if (floating && isIterateeCall(min, max, floating)) {
	        max = floating = null;
	      }
	      var noMin = min == null,
	          noMax = max == null;

	      if (floating == null) {
	        if (noMax && typeof min == 'boolean') {
	          floating = min;
	          min = 1;
	        }
	        else if (typeof max == 'boolean') {
	          floating = max;
	          noMax = true;
	        }
	      }
	      if (noMin && noMax) {
	        max = 1;
	        noMax = false;
	      }
	      min = +min || 0;
	      if (noMax) {
	        max = min;
	        min = 0;
	      } else {
	        max = +max || 0;
	      }
	      if (floating || min % 1 || max % 1) {
	        var rand = nativeRandom();
	        return nativeMin(min + (rand * (max - min + parseFloat('1e-' + ((rand + '').length - 1)))), max);
	      }
	      return baseRandom(min, max);
	    }

	    /*------------------------------------------------------------------------*/

	    /**
	     * Converts `string` to camel case.
	     * See [Wikipedia](https://en.wikipedia.org/wiki/CamelCase) for more details.
	     *
	     * @static
	     * @memberOf _
	     * @category String
	     * @param {string} [string=''] The string to convert.
	     * @returns {string} Returns the camel cased string.
	     * @example
	     *
	     * _.camelCase('Foo Bar');
	     * // => 'fooBar'
	     *
	     * _.camelCase('--foo-bar');
	     * // => 'fooBar'
	     *
	     * _.camelCase('__foo_bar__');
	     * // => 'fooBar'
	     */
	    var camelCase = createCompounder(function(result, word, index) {
	      word = word.toLowerCase();
	      return result + (index ? (word.charAt(0).toUpperCase() + word.slice(1)) : word);
	    });

	    /**
	     * Capitalizes the first character of `string`.
	     *
	     * @static
	     * @memberOf _
	     * @category String
	     * @param {string} [string=''] The string to capitalize.
	     * @returns {string} Returns the capitalized string.
	     * @example
	     *
	     * _.capitalize('fred');
	     * // => 'Fred'
	     */
	    function capitalize(string) {
	      string = baseToString(string);
	      return string && (string.charAt(0).toUpperCase() + string.slice(1));
	    }

	    /**
	     * Deburrs `string` by converting latin-1 supplementary letters to basic latin letters.
	     * See [Wikipedia](https://en.wikipedia.org/wiki/Latin-1_Supplement_(Unicode_block)#Character_table)
	     * for more details.
	     *
	     * @static
	     * @memberOf _
	     * @category String
	     * @param {string} [string=''] The string to deburr.
	     * @returns {string} Returns the deburred string.
	     * @example
	     *
	     * _.deburr('déjà vu');
	     * // => 'deja vu'
	     */
	    function deburr(string) {
	      string = baseToString(string);
	      return string && string.replace(reLatin1, deburrLetter);
	    }

	    /**
	     * Checks if `string` ends with the given target string.
	     *
	     * @static
	     * @memberOf _
	     * @category String
	     * @param {string} [string=''] The string to search.
	     * @param {string} [target] The string to search for.
	     * @param {number} [position=string.length] The position to search from.
	     * @returns {boolean} Returns `true` if `string` ends with `target`, else `false`.
	     * @example
	     *
	     * _.endsWith('abc', 'c');
	     * // => true
	     *
	     * _.endsWith('abc', 'b');
	     * // => false
	     *
	     * _.endsWith('abc', 'b', 2);
	     * // => true
	     */
	    function endsWith(string, target, position) {
	      string = baseToString(string);
	      target = (target + '');

	      var length = string.length;
	      position = (typeof position == 'undefined' ? length : nativeMin(position < 0 ? 0 : (+position || 0), length)) - target.length;
	      return position >= 0 && string.indexOf(target, position) == position;
	    }

	    /**
	     * Converts the characters "&", "<", ">", '"', "'", and '`', in `string` to
	     * their corresponding HTML entities.
	     *
	     * **Note:** No other characters are escaped. To escape additional characters
	     * use a third-party library like [_he_](https://mths.be/he).
	     *
	     * Though the ">" character is escaped for symmetry, characters like
	     * ">" and "/" don't require escaping in HTML and have no special meaning
	     * unless they're part of a tag or unquoted attribute value.
	     * See [Mathias Bynens's article](https://mathiasbynens.be/notes/ambiguous-ampersands)
	     * (under "semi-related fun fact") for more details.
	     *
	     * Backticks are escaped because in Internet Explorer < 9, they can break out
	     * of attribute values or HTML comments. See [#102](https://html5sec.org/#102),
	     * [#108](https://html5sec.org/#108), and [#133](https://html5sec.org/#133) of
	     * the [HTML5 Security Cheatsheet](https://html5sec.org/) for more details.
	     *
	     * When working with HTML you should always quote attribute values to reduce
	     * XSS vectors. See [Ryan Grove's article](http://wonko.com/post/html-escaping)
	     * for more details.
	     *
	     * @static
	     * @memberOf _
	     * @category String
	     * @param {string} [string=''] The string to escape.
	     * @returns {string} Returns the escaped string.
	     * @example
	     *
	     * _.escape('fred, barney, & pebbles');
	     * // => 'fred, barney, &amp; pebbles'
	     */
	    function escape(string) {
	      // Reset `lastIndex` because in IE < 9 `String#replace` does not.
	      string = baseToString(string);
	      return (string && reHasUnescapedHtml.test(string))
	        ? string.replace(reUnescapedHtml, escapeHtmlChar)
	        : string;
	    }

	    /**
	     * Escapes the `RegExp` special characters "\", "^", "$", ".", "|", "?", "*",
	     * "+", "(", ")", "[", "]", "{" and "}" in `string`.
	     *
	     * @static
	     * @memberOf _
	     * @category String
	     * @param {string} [string=''] The string to escape.
	     * @returns {string} Returns the escaped string.
	     * @example
	     *
	     * _.escapeRegExp('[lodash](https://lodash.com/)');
	     * // => '\[lodash\]\(https://lodash\.com/\)'
	     */
	    function escapeRegExp(string) {
	      string = baseToString(string);
	      return (string && reHasRegExpChars.test(string))
	        ? string.replace(reRegExpChars, '\\$&')
	        : string;
	    }

	    /**
	     * Converts `string` to kebab case (a.k.a. spinal case).
	     * See [Wikipedia](https://en.wikipedia.org/wiki/Letter_case#Special_case_styles) for
	     * more details.
	     *
	     * @static
	     * @memberOf _
	     * @category String
	     * @param {string} [string=''] The string to convert.
	     * @returns {string} Returns the kebab cased string.
	     * @example
	     *
	     * _.kebabCase('Foo Bar');
	     * // => 'foo-bar'
	     *
	     * _.kebabCase('fooBar');
	     * // => 'foo-bar'
	     *
	     * _.kebabCase('__foo_bar__');
	     * // => 'foo-bar'
	     */
	    var kebabCase = createCompounder(function(result, word, index) {
	      return result + (index ? '-' : '') + word.toLowerCase();
	    });

	    /**
	     * Pads `string` on the left and right sides if it is shorter then the given
	     * padding length. The `chars` string may be truncated if the number of padding
	     * characters can't be evenly divided by the padding length.
	     *
	     * @static
	     * @memberOf _
	     * @category String
	     * @param {string} [string=''] The string to pad.
	     * @param {number} [length=0] The padding length.
	     * @param {string} [chars=' '] The string used as padding.
	     * @returns {string} Returns the padded string.
	     * @example
	     *
	     * _.pad('abc', 8);
	     * // => '  abc   '
	     *
	     * _.pad('abc', 8, '_-');
	     * // => '_-abc_-_'
	     *
	     * _.pad('abc', 3);
	     * // => 'abc'
	     */
	    function pad(string, length, chars) {
	      string = baseToString(string);
	      length = +length;

	      var strLength = string.length;
	      if (strLength >= length || !nativeIsFinite(length)) {
	        return string;
	      }
	      var mid = (length - strLength) / 2,
	          leftLength = floor(mid),
	          rightLength = ceil(mid);

	      chars = createPad('', rightLength, chars);
	      return chars.slice(0, leftLength) + string + chars;
	    }

	    /**
	     * Pads `string` on the left side if it is shorter then the given padding
	     * length. The `chars` string may be truncated if the number of padding
	     * characters exceeds the padding length.
	     *
	     * @static
	     * @memberOf _
	     * @category String
	     * @param {string} [string=''] The string to pad.
	     * @param {number} [length=0] The padding length.
	     * @param {string} [chars=' '] The string used as padding.
	     * @returns {string} Returns the padded string.
	     * @example
	     *
	     * _.padLeft('abc', 6);
	     * // => '   abc'
	     *
	     * _.padLeft('abc', 6, '_-');
	     * // => '_-_abc'
	     *
	     * _.padLeft('abc', 3);
	     * // => 'abc'
	     */
	    function padLeft(string, length, chars) {
	      string = baseToString(string);
	      return string && (createPad(string, length, chars) + string);
	    }

	    /**
	     * Pads `string` on the right side if it is shorter then the given padding
	     * length. The `chars` string may be truncated if the number of padding
	     * characters exceeds the padding length.
	     *
	     * @static
	     * @memberOf _
	     * @category String
	     * @param {string} [string=''] The string to pad.
	     * @param {number} [length=0] The padding length.
	     * @param {string} [chars=' '] The string used as padding.
	     * @returns {string} Returns the padded string.
	     * @example
	     *
	     * _.padRight('abc', 6);
	     * // => 'abc   '
	     *
	     * _.padRight('abc', 6, '_-');
	     * // => 'abc_-_'
	     *
	     * _.padRight('abc', 3);
	     * // => 'abc'
	     */
	    function padRight(string, length, chars) {
	      string = baseToString(string);
	      return string && (string + createPad(string, length, chars));
	    }

	    /**
	     * Converts `string` to an integer of the specified radix. If `radix` is
	     * `undefined` or `0`, a `radix` of `10` is used unless `value` is a hexadecimal,
	     * in which case a `radix` of `16` is used.
	     *
	     * **Note:** This method aligns with the ES5 implementation of `parseInt`.
	     * See the [ES5 spec](https://es5.github.io/#E) for more details.
	     *
	     * @static
	     * @memberOf _
	     * @category String
	     * @param {string} string The string to convert.
	     * @param {number} [radix] The radix to interpret `value` by.
	     * @param- {Object} [guard] Enables use as a callback for functions like `_.map`.
	     * @returns {number} Returns the converted integer.
	     * @example
	     *
	     * _.parseInt('08');
	     * // => 8
	     *
	     * _.map(['6', '08', '10'], _.parseInt);
	     * // => [6, 8, 10]
	     */
	    function parseInt(string, radix, guard) {
	      if (guard && isIterateeCall(string, radix, guard)) {
	        radix = 0;
	      }
	      return nativeParseInt(string, radix);
	    }
	    // Fallback for environments with pre-ES5 implementations.
	    if (nativeParseInt(whitespace + '08') != 8) {
	      parseInt = function(string, radix, guard) {
	        // Firefox < 21 and Opera < 15 follow ES3 for `parseInt`.
	        // Chrome fails to trim leading <BOM> whitespace characters.
	        // See https://code.google.com/p/v8/issues/detail?id=3109 for more details.
	        if (guard ? isIterateeCall(string, radix, guard) : radix == null) {
	          radix = 0;
	        } else if (radix) {
	          radix = +radix;
	        }
	        string = trim(string);
	        return nativeParseInt(string, radix || (reHexPrefix.test(string) ? 16 : 10));
	      };
	    }

	    /**
	     * Repeats the given string `n` times.
	     *
	     * @static
	     * @memberOf _
	     * @category String
	     * @param {string} [string=''] The string to repeat.
	     * @param {number} [n=0] The number of times to repeat the string.
	     * @returns {string} Returns the repeated string.
	     * @example
	     *
	     * _.repeat('*', 3);
	     * // => '***'
	     *
	     * _.repeat('abc', 2);
	     * // => 'abcabc'
	     *
	     * _.repeat('abc', 0);
	     * // => ''
	     */
	    function repeat(string, n) {
	      var result = '';
	      string = baseToString(string);
	      n = +n;
	      if (n < 1 || !string || !nativeIsFinite(n)) {
	        return result;
	      }
	      // Leverage the exponentiation by squaring algorithm for a faster repeat.
	      // See https://en.wikipedia.org/wiki/Exponentiation_by_squaring for more details.
	      do {
	        if (n % 2) {
	          result += string;
	        }
	        n = floor(n / 2);
	        string += string;
	      } while (n);

	      return result;
	    }

	    /**
	     * Converts `string` to snake case.
	     * See [Wikipedia](https://en.wikipedia.org/wiki/Snake_case) for more details.
	     *
	     * @static
	     * @memberOf _
	     * @category String
	     * @param {string} [string=''] The string to convert.
	     * @returns {string} Returns the snake cased string.
	     * @example
	     *
	     * _.snakeCase('Foo Bar');
	     * // => 'foo_bar'
	     *
	     * _.snakeCase('fooBar');
	     * // => 'foo_bar'
	     *
	     * _.snakeCase('--foo-bar');
	     * // => 'foo_bar'
	     */
	    var snakeCase = createCompounder(function(result, word, index) {
	      return result + (index ? '_' : '') + word.toLowerCase();
	    });

	    /**
	     * Converts `string` to start case.
	     * See [Wikipedia](https://en.wikipedia.org/wiki/Letter_case#Stylistic_or_specialised_usage)
	     * for more details.
	     *
	     * @static
	     * @memberOf _
	     * @category String
	     * @param {string} [string=''] The string to convert.
	     * @returns {string} Returns the start cased string.
	     * @example
	     *
	     * _.startCase('--foo-bar');
	     * // => 'Foo Bar'
	     *
	     * _.startCase('fooBar');
	     * // => 'Foo Bar'
	     *
	     * _.startCase('__foo_bar__');
	     * // => 'Foo Bar'
	     */
	    var startCase = createCompounder(function(result, word, index) {
	      return result + (index ? ' ' : '') + (word.charAt(0).toUpperCase() + word.slice(1));
	    });

	    /**
	     * Checks if `string` starts with the given target string.
	     *
	     * @static
	     * @memberOf _
	     * @category String
	     * @param {string} [string=''] The string to search.
	     * @param {string} [target] The string to search for.
	     * @param {number} [position=0] The position to search from.
	     * @returns {boolean} Returns `true` if `string` starts with `target`, else `false`.
	     * @example
	     *
	     * _.startsWith('abc', 'a');
	     * // => true
	     *
	     * _.startsWith('abc', 'b');
	     * // => false
	     *
	     * _.startsWith('abc', 'b', 1);
	     * // => true
	     */
	    function startsWith(string, target, position) {
	      string = baseToString(string);
	      position = position == null ? 0 : nativeMin(position < 0 ? 0 : (+position || 0), string.length);
	      return string.lastIndexOf(target, position) == position;
	    }

	    /**
	     * Creates a compiled template function that can interpolate data properties
	     * in "interpolate" delimiters, HTML-escape interpolated data properties in
	     * "escape" delimiters, and execute JavaScript in "evaluate" delimiters. Data
	     * properties may be accessed as free variables in the template. If a setting
	     * object is provided it takes precedence over `_.templateSettings` values.
	     *
	     * **Note:** In the development build `_.template` utilizes sourceURLs for easier debugging.
	     * See the [HTML5 Rocks article on sourcemaps](http://www.html5rocks.com/en/tutorials/developertools/sourcemaps/#toc-sourceurl)
	     * for more details.
	     *
	     * For more information on precompiling templates see
	     * [lodash's custom builds documentation](https://lodash.com/custom-builds).
	     *
	     * For more information on Chrome extension sandboxes see
	     * [Chrome's extensions documentation](https://developer.chrome.com/extensions/sandboxingEval).
	     *
	     * @static
	     * @memberOf _
	     * @category String
	     * @param {string} [string=''] The template string.
	     * @param {Object} [options] The options object.
	     * @param {RegExp} [options.escape] The HTML "escape" delimiter.
	     * @param {RegExp} [options.evaluate] The "evaluate" delimiter.
	     * @param {Object} [options.imports] An object to import into the template as free variables.
	     * @param {RegExp} [options.interpolate] The "interpolate" delimiter.
	     * @param {string} [options.sourceURL] The sourceURL of the template's compiled source.
	     * @param {string} [options.variable] The data object variable name.
	     * @param- {Object} [otherOptions] Enables the legacy `options` param signature.
	     * @returns {Function} Returns the compiled template function.
	     * @example
	     *
	     * // using the "interpolate" delimiter to create a compiled template
	     * var compiled = _.template('hello <%= user %>!');
	     * compiled({ 'user': 'fred' });
	     * // => 'hello fred!'
	     *
	     * // using the HTML "escape" delimiter to escape data property values
	     * var compiled = _.template('<b><%- value %></b>');
	     * compiled({ 'value': '<script>' });
	     * // => '<b>&lt;script&gt;</b>'
	     *
	     * // using the "evaluate" delimiter to execute JavaScript and generate HTML
	     * var compiled = _.template('<% _.forEach(users, function(user) { %><li><%- user %></li><% }); %>');
	     * compiled({ 'users': ['fred', 'barney'] });
	     * // => '<li>fred</li><li>barney</li>'
	     *
	     * // using the internal `print` function in "evaluate" delimiters
	     * var compiled = _.template('<% print("hello " + user); %>!');
	     * compiled({ 'user': 'barney' });
	     * // => 'hello barney!'
	     *
	     * // using the ES delimiter as an alternative to the default "interpolate" delimiter
	     * var compiled = _.template('hello ${ user }!');
	     * compiled({ 'user': 'pebbles' });
	     * // => 'hello pebbles!'
	     *
	     * // using custom template delimiters
	     * _.templateSettings.interpolate = /{{([\s\S]+?)}}/g;
	     * var compiled = _.template('hello {{ user }}!');
	     * compiled({ 'user': 'mustache' });
	     * // => 'hello mustache!'
	     *
	     * // using backslashes to treat delimiters as plain text
	     * var compiled = _.template('<%= "\\<%- value %\\>" %>');
	     * compiled({ 'value': 'ignored' });
	     * // => '<%- value %>'
	     *
	     * // using the `imports` option to import `jQuery` as `jq`
	     * var text = '<% jq.each(users, function(user) { %><li><%- user %></li><% }); %>';
	     * var compiled = _.template(text, { 'imports': { 'jq': jQuery } });
	     * compiled({ 'users': ['fred', 'barney'] });
	     * // => '<li>fred</li><li>barney</li>'
	     *
	     * // using the `sourceURL` option to specify a custom sourceURL for the template
	     * var compiled = _.template('hello <%= user %>!', { 'sourceURL': '/basic/greeting.jst' });
	     * compiled(data);
	     * // => find the source of "greeting.jst" under the Sources tab or Resources panel of the web inspector
	     *
	     * // using the `variable` option to ensure a with-statement isn't used in the compiled template
	     * var compiled = _.template('hi <%= data.user %>!', { 'variable': 'data' });
	     * compiled.source;
	     * // => function(data) {
	     *   var __t, __p = '';
	     *   __p += 'hi ' + ((__t = ( data.user )) == null ? '' : __t) + '!';
	     *   return __p;
	     * }
	     *
	     * // using the `source` property to inline compiled templates for meaningful
	     * // line numbers in error messages and a stack trace
	     * fs.writeFileSync(path.join(cwd, 'jst.js'), '\
	     *   var JST = {\
	     *     "main": ' + _.template(mainText).source + '\
	     *   };\
	     * ');
	     */
	    function template(string, options, otherOptions) {
	      // Based on John Resig's `tmpl` implementation (http://ejohn.org/blog/javascript-micro-templating/)
	      // and Laura Doktorova's doT.js (https://github.com/olado/doT).
	      var settings = lodash.templateSettings;

	      if (otherOptions && isIterateeCall(string, options, otherOptions)) {
	        options = otherOptions = null;
	      }
	      string = baseToString(string);
	      options = baseAssign(baseAssign({}, otherOptions || options), settings, assignOwnDefaults);

	      var imports = baseAssign(baseAssign({}, options.imports), settings.imports, assignOwnDefaults),
	          importsKeys = keys(imports),
	          importsValues = baseValues(imports, importsKeys);

	      var isEscaping,
	          isEvaluating,
	          index = 0,
	          interpolate = options.interpolate || reNoMatch,
	          source = "__p += '";

	      // Compile the regexp to match each delimiter.
	      var reDelimiters = RegExp(
	        (options.escape || reNoMatch).source + '|' +
	        interpolate.source + '|' +
	        (interpolate === reInterpolate ? reEsTemplate : reNoMatch).source + '|' +
	        (options.evaluate || reNoMatch).source + '|$'
	      , 'g');

	      // Use a sourceURL for easier debugging.
	      var sourceURL = '//# sourceURL=' +
	        ('sourceURL' in options
	          ? options.sourceURL
	          : ('lodash.templateSources[' + (++templateCounter) + ']')
	        ) + '\n';

	      string.replace(reDelimiters, function(match, escapeValue, interpolateValue, esTemplateValue, evaluateValue, offset) {
	        interpolateValue || (interpolateValue = esTemplateValue);

	        // Escape characters that can't be included in string literals.
	        source += string.slice(index, offset).replace(reUnescapedString, escapeStringChar);

	        // Replace delimiters with snippets.
	        if (escapeValue) {
	          isEscaping = true;
	          source += "' +\n__e(" + escapeValue + ") +\n'";
	        }
	        if (evaluateValue) {
	          isEvaluating = true;
	          source += "';\n" + evaluateValue + ";\n__p += '";
	        }
	        if (interpolateValue) {
	          source += "' +\n((__t = (" + interpolateValue + ")) == null ? '' : __t) +\n'";
	        }
	        index = offset + match.length;

	        // The JS engine embedded in Adobe products requires returning the `match`
	        // string in order to produce the correct `offset` value.
	        return match;
	      });

	      source += "';\n";

	      // If `variable` is not specified wrap a with-statement around the generated
	      // code to add the data object to the top of the scope chain.
	      var variable = options.variable;
	      if (!variable) {
	        source = 'with (obj) {\n' + source + '\n}\n';
	      }
	      // Cleanup code by stripping empty strings.
	      source = (isEvaluating ? source.replace(reEmptyStringLeading, '') : source)
	        .replace(reEmptyStringMiddle, '$1')
	        .replace(reEmptyStringTrailing, '$1;');

	      // Frame code as the function body.
	      source = 'function(' + (variable || 'obj') + ') {\n' +
	        (variable
	          ? ''
	          : 'obj || (obj = {});\n'
	        ) +
	        "var __t, __p = ''" +
	        (isEscaping
	           ? ', __e = _.escape'
	           : ''
	        ) +
	        (isEvaluating
	          ? ', __j = Array.prototype.join;\n' +
	            "function print() { __p += __j.call(arguments, '') }\n"
	          : ';\n'
	        ) +
	        source +
	        'return __p\n}';

	      var result = attempt(function() {
	        return Function(importsKeys, sourceURL + 'return ' + source).apply(undefined, importsValues);
	      });

	      // Provide the compiled function's source by its `toString` method or
	      // the `source` property as a convenience for inlining compiled templates.
	      result.source = source;
	      if (isError(result)) {
	        throw result;
	      }
	      return result;
	    }

	    /**
	     * Removes leading and trailing whitespace or specified characters from `string`.
	     *
	     * @static
	     * @memberOf _
	     * @category String
	     * @param {string} [string=''] The string to trim.
	     * @param {string} [chars=whitespace] The characters to trim.
	     * @param- {Object} [guard] Enables use as a callback for functions like `_.map`.
	     * @returns {string} Returns the trimmed string.
	     * @example
	     *
	     * _.trim('  abc  ');
	     * // => 'abc'
	     *
	     * _.trim('-_-abc-_-', '_-');
	     * // => 'abc'
	     *
	     * _.map(['  foo  ', '  bar  '], _.trim);
	     * // => ['foo', 'bar]
	     */
	    function trim(string, chars, guard) {
	      var value = string;
	      string = baseToString(string);
	      if (!string) {
	        return string;
	      }
	      if (guard ? isIterateeCall(value, chars, guard) : chars == null) {
	        return string.slice(trimmedLeftIndex(string), trimmedRightIndex(string) + 1);
	      }
	      chars = (chars + '');
	      return string.slice(charsLeftIndex(string, chars), charsRightIndex(string, chars) + 1);
	    }

	    /**
	     * Removes leading whitespace or specified characters from `string`.
	     *
	     * @static
	     * @memberOf _
	     * @category String
	     * @param {string} [string=''] The string to trim.
	     * @param {string} [chars=whitespace] The characters to trim.
	     * @param- {Object} [guard] Enables use as a callback for functions like `_.map`.
	     * @returns {string} Returns the trimmed string.
	     * @example
	     *
	     * _.trimLeft('  abc  ');
	     * // => 'abc  '
	     *
	     * _.trimLeft('-_-abc-_-', '_-');
	     * // => 'abc-_-'
	     */
	    function trimLeft(string, chars, guard) {
	      var value = string;
	      string = baseToString(string);
	      if (!string) {
	        return string;
	      }
	      if (guard ? isIterateeCall(value, chars, guard) : chars == null) {
	        return string.slice(trimmedLeftIndex(string))
	      }
	      return string.slice(charsLeftIndex(string, (chars + '')));
	    }

	    /**
	     * Removes trailing whitespace or specified characters from `string`.
	     *
	     * @static
	     * @memberOf _
	     * @category String
	     * @param {string} [string=''] The string to trim.
	     * @param {string} [chars=whitespace] The characters to trim.
	     * @param- {Object} [guard] Enables use as a callback for functions like `_.map`.
	     * @returns {string} Returns the trimmed string.
	     * @example
	     *
	     * _.trimRight('  abc  ');
	     * // => '  abc'
	     *
	     * _.trimRight('-_-abc-_-', '_-');
	     * // => '-_-abc'
	     */
	    function trimRight(string, chars, guard) {
	      var value = string;
	      string = baseToString(string);
	      if (!string) {
	        return string;
	      }
	      if (guard ? isIterateeCall(value, chars, guard) : chars == null) {
	        return string.slice(0, trimmedRightIndex(string) + 1)
	      }
	      return string.slice(0, charsRightIndex(string, (chars + '')) + 1);
	    }

	    /**
	     * Truncates `string` if it is longer than the given maximum string length.
	     * The last characters of the truncated string are replaced with the omission
	     * string which defaults to "...".
	     *
	     * @static
	     * @memberOf _
	     * @category String
	     * @param {string} [string=''] The string to truncate.
	     * @param {Object|number} [options] The options object or maximum string length.
	     * @param {number} [options.length=30] The maximum string length.
	     * @param {string} [options.omission='...'] The string to indicate text is omitted.
	     * @param {RegExp|string} [options.separator] The separator pattern to truncate to.
	     * @param- {Object} [guard] Enables use as a callback for functions like `_.map`.
	     * @returns {string} Returns the truncated string.
	     * @example
	     *
	     * _.trunc('hi-diddly-ho there, neighborino');
	     * // => 'hi-diddly-ho there, neighbo...'
	     *
	     * _.trunc('hi-diddly-ho there, neighborino', 24);
	     * // => 'hi-diddly-ho there, n...'
	     *
	     * _.trunc('hi-diddly-ho there, neighborino', { 'length': 24, 'separator': ' ' });
	     * // => 'hi-diddly-ho there,...'
	     *
	     * _.trunc('hi-diddly-ho there, neighborino', { 'length': 24, 'separator': /,? +/ });
	     * //=> 'hi-diddly-ho there...'
	     *
	     * _.trunc('hi-diddly-ho there, neighborino', { 'omission': ' [...]' });
	     * // => 'hi-diddly-ho there, neig [...]'
	     */
	    function trunc(string, options, guard) {
	      if (guard && isIterateeCall(string, options, guard)) {
	        options = null;
	      }
	      var length = DEFAULT_TRUNC_LENGTH,
	          omission = DEFAULT_TRUNC_OMISSION;

	      if (options != null) {
	        if (isObject(options)) {
	          var separator = 'separator' in options ? options.separator : separator;
	          length = 'length' in options ? +options.length || 0 : length;
	          omission = 'omission' in options ? baseToString(options.omission) : omission;
	        } else {
	          length = +options || 0;
	        }
	      }
	      string = baseToString(string);
	      if (length >= string.length) {
	        return string;
	      }
	      var end = length - omission.length;
	      if (end < 1) {
	        return omission;
	      }
	      var result = string.slice(0, end);
	      if (separator == null) {
	        return result + omission;
	      }
	      if (isRegExp(separator)) {
	        if (string.slice(end).search(separator)) {
	          var match,
	              newEnd,
	              substring = string.slice(0, end);

	          if (!separator.global) {
	            separator = RegExp(separator.source, (reFlags.exec(separator) || '') + 'g');
	          }
	          separator.lastIndex = 0;
	          while ((match = separator.exec(substring))) {
	            newEnd = match.index;
	          }
	          result = result.slice(0, newEnd == null ? end : newEnd);
	        }
	      } else if (string.indexOf(separator, end) != end) {
	        var index = result.lastIndexOf(separator);
	        if (index > -1) {
	          result = result.slice(0, index);
	        }
	      }
	      return result + omission;
	    }

	    /**
	     * The inverse of `_.escape`; this method converts the HTML entities
	     * `&amp;`, `&lt;`, `&gt;`, `&quot;`, `&#39;`, and `&#96;` in `string` to their
	     * corresponding characters.
	     *
	     * **Note:** No other HTML entities are unescaped. To unescape additional HTML
	     * entities use a third-party library like [_he_](https://mths.be/he).
	     *
	     * @static
	     * @memberOf _
	     * @category String
	     * @param {string} [string=''] The string to unescape.
	     * @returns {string} Returns the unescaped string.
	     * @example
	     *
	     * _.unescape('fred, barney, &amp; pebbles');
	     * // => 'fred, barney, & pebbles'
	     */
	    function unescape(string) {
	      string = baseToString(string);
	      return (string && reHasEscapedHtml.test(string))
	        ? string.replace(reEscapedHtml, unescapeHtmlChar)
	        : string;
	    }

	    /**
	     * Splits `string` into an array of its words.
	     *
	     * @static
	     * @memberOf _
	     * @category String
	     * @param {string} [string=''] The string to inspect.
	     * @param {RegExp|string} [pattern] The pattern to match words.
	     * @param- {Object} [guard] Enables use as a callback for functions like `_.map`.
	     * @returns {Array} Returns the words of `string`.
	     * @example
	     *
	     * _.words('fred, barney, & pebbles');
	     * // => ['fred', 'barney', 'pebbles']
	     *
	     * _.words('fred, barney, & pebbles', /[^, ]+/g);
	     * // => ['fred', 'barney', '&', 'pebbles']
	     */
	    function words(string, pattern, guard) {
	      if (guard && isIterateeCall(string, pattern, guard)) {
	        pattern = null;
	      }
	      string = baseToString(string);
	      return string.match(pattern || reWords) || [];
	    }

	    /*------------------------------------------------------------------------*/

	    /**
	     * Attempts to invoke `func`, returning either the result or the caught
	     * error object.
	     *
	     * @static
	     * @memberOf _
	     * @category Utility
	     * @param {*} func The function to attempt.
	     * @returns {*} Returns the `func` result or error object.
	     * @example
	     *
	     * // avoid throwing errors for invalid selectors
	     * var elements = _.attempt(function() {
	     *   return document.querySelectorAll(selector);
	     * });
	     *
	     * if (_.isError(elements)) {
	     *   elements = [];
	     * }
	     */
	    function attempt(func) {
	      try {
	        return func();
	      } catch(e) {
	        return isError(e) ? e : Error(e);
	      }
	    }

	    /**
	     * Creates a function bound to an optional `thisArg`. If `func` is a property
	     * name the created callback returns the property value for a given element.
	     * If `func` is an object the created callback returns `true` for elements
	     * that contain the equivalent object properties, otherwise it returns `false`.
	     *
	     * @static
	     * @memberOf _
	     * @alias iteratee
	     * @category Utility
	     * @param {*} [func=_.identity] The value to convert to a callback.
	     * @param {*} [thisArg] The `this` binding of `func`.
	     * @param- {Object} [guard] Enables use as a callback for functions like `_.map`.
	     * @returns {Function} Returns the callback.
	     * @example
	     *
	     * var users = [
	     *   { 'user': 'barney', 'age': 36 },
	     *   { 'user': 'fred',   'age': 40 }
	     * ];
	     *
	     * // wrap to create custom callback shorthands
	     * _.callback = _.wrap(_.callback, function(callback, func, thisArg) {
	     *   var match = /^(.+?)__([gl]t)(.+)$/.exec(func);
	     *   if (!match) {
	     *     return callback(func, thisArg);
	     *   }
	     *   return function(object) {
	     *     return match[2] == 'gt' ? object[match[1]] > match[3] : object[match[1]] < match[3];
	     *   };
	     * });
	     *
	     * _.filter(users, 'age__gt36');
	     * // => [{ 'user': 'fred', 'age': 40 }]
	     */
	    function callback(func, thisArg, guard) {
	      if (guard && isIterateeCall(func, thisArg, guard)) {
	        thisArg = null;
	      }
	      return isObjectLike(func)
	        ? matches(func)
	        : baseCallback(func, thisArg);
	    }

	    /**
	     * Creates a function that returns `value`.
	     *
	     * @static
	     * @memberOf _
	     * @category Utility
	     * @param {*} value The value to return from the new function.
	     * @returns {Function} Returns the new function.
	     * @example
	     *
	     * var object = { 'user': 'fred' };
	     * var getter = _.constant(object);
	     * getter() === object;
	     * // => true
	     */
	    function constant(value) {
	      return function() {
	        return value;
	      };
	    }

	    /**
	     * This method returns the first argument provided to it.
	     *
	     * @static
	     * @memberOf _
	     * @category Utility
	     * @param {*} value Any value.
	     * @returns {*} Returns `value`.
	     * @example
	     *
	     * var object = { 'user': 'fred' };
	     * _.identity(object) === object;
	     * // => true
	     */
	    function identity(value) {
	      return value;
	    }

	    /**
	     * Creates a function which performs a deep comparison between a given object
	     * and `source`, returning `true` if the given object has equivalent property
	     * values, else `false`.
	     *
	     * @static
	     * @memberOf _
	     * @category Utility
	     * @param {Object} source The object of property values to match.
	     * @returns {Function} Returns the new function.
	     * @example
	     *
	     * var users = [
	     *   { 'user': 'fred',   'age': 40 },
	     *   { 'user': 'barney', 'age': 36 }
	     * ];
	     *
	     * var matchesAge = _.matches({ 'age': 36 });
	     *
	     * _.filter(users, matchesAge);
	     * // => [{ 'user': 'barney', 'age': 36 }]
	     *
	     * _.find(users, matchesAge);
	     * // => { 'user': 'barney', 'age': 36 }
	     */
	    function matches(source) {
	      return baseMatches(baseClone(source, true));
	    }

	    /**
	     * Adds all own enumerable function properties of a source object to the
	     * destination object. If `object` is a function then methods are added to
	     * its prototype as well.
	     *
	     * @static
	     * @memberOf _
	     * @category Utility
	     * @param {Function|Object} [object=this] object The destination object.
	     * @param {Object} source The object of functions to add.
	     * @param {Object} [options] The options object.
	     * @param {boolean} [options.chain=true] Specify whether the functions added
	     *  are chainable.
	     * @returns {Function|Object} Returns `object`.
	     * @example
	     *
	     * function vowels(string) {
	     *   return _.filter(string, function(v) {
	     *     return /[aeiou]/i.test(v);
	     *   });
	     * }
	     *
	     * _.mixin({ 'vowels': vowels });
	     * _.vowels('fred');
	     * // => ['e']
	     *
	     * _('fred').vowels().value();
	     * // => ['e']
	     *
	     * _.mixin({ 'vowels': vowels }, { 'chain': false });
	     * _('fred').vowels();
	     * // => ['e']
	     */
	    function mixin(object, source, options) {
	      if (options == null) {
	        var isObj = isObject(source),
	            props = isObj && keys(source),
	            methodNames = props && props.length && baseFunctions(source, props);

	        if (!(methodNames ? methodNames.length : isObj)) {
	          methodNames = false;
	          options = source;
	          source = object;
	          object = this;
	        }
	      }
	      if (!methodNames) {
	        methodNames = baseFunctions(source, keys(source));
	      }
	      var chain = true,
	          index = -1,
	          isFunc = isFunction(object),
	          length = methodNames.length;

	      if (options === false) {
	        chain = false;
	      } else if (isObject(options) && 'chain' in options) {
	        chain = options.chain;
	      }
	      while (++index < length) {
	        var methodName = methodNames[index],
	            func = source[methodName];

	        object[methodName] = func;
	        if (isFunc) {
	          object.prototype[methodName] = (function(func) {
	            return function() {
	              var chainAll = this.__chain__;
	              if (chain || chainAll) {
	                var result = object(this.__wrapped__);
	                (result.__actions__ = arrayCopy(this.__actions__)).push({ 'func': func, 'args': arguments, 'thisArg': object });
	                result.__chain__ = chainAll;
	                return result;
	              }
	              var args = [this.value()];
	              push.apply(args, arguments);
	              return func.apply(object, args);
	            };
	          }(func));
	        }
	      }
	      return object;
	    }

	    /**
	     * Reverts the `_` variable to its previous value and returns a reference to
	     * the `lodash` function.
	     *
	     * @static
	     * @memberOf _
	     * @category Utility
	     * @returns {Function} Returns the `lodash` function.
	     * @example
	     *
	     * var lodash = _.noConflict();
	     */
	    function noConflict() {
	      context._ = oldDash;
	      return this;
	    }

	    /**
	     * A no-operation function.
	     *
	     * @static
	     * @memberOf _
	     * @category Utility
	     * @example
	     *
	     * var object = { 'user': 'fred' };
	     * _.noop(object) === undefined;
	     * // => true
	     */
	    function noop() {
	      // No operation performed.
	    }

	    /**
	     * Creates a function which returns the property value of `key` on a given object.
	     *
	     * @static
	     * @memberOf _
	     * @category Utility
	     * @param {string} key The key of the property to get.
	     * @returns {Function} Returns the new function.
	     * @example
	     *
	     * var users = [
	     *   { 'user': 'fred' },
	     *   { 'user': 'barney' }
	     * ];
	     *
	     * var getName = _.property('user');
	     *
	     * _.map(users, getName);
	     * // => ['fred', barney']
	     *
	     * _.pluck(_.sortBy(users, getName), 'user');
	     * // => ['barney', 'fred']
	     */
	    function property(key) {
	      return baseProperty(key + '');
	    }

	    /**
	     * The inverse of `_.property`; this method creates a function which returns
	     * the property value of a given key on `object`.
	     *
	     * @static
	     * @memberOf _
	     * @category Utility
	     * @param {Object} object The object to inspect.
	     * @returns {Function} Returns the new function.
	     * @example
	     *
	     * var object = { 'user': 'fred', 'age': 40, 'active': true };
	     * _.map(['active', 'user'], _.propertyOf(object));
	     * // => [true, 'fred']
	     *
	     * var object = { 'a': 3, 'b': 1, 'c': 2 };
	     * _.sortBy(['a', 'b', 'c'], _.propertyOf(object));
	     * // => ['b', 'c', 'a']
	     */
	    function propertyOf(object) {
	      return function(key) {
	        return object == null ? undefined : object[key];
	      };
	    }

	    /**
	     * Creates an array of numbers (positive and/or negative) progressing from
	     * `start` up to, but not including, `end`. If `start` is less than `end` a
	     * zero-length range is created unless a negative `step` is specified.
	     *
	     * @static
	     * @memberOf _
	     * @category Utility
	     * @param {number} [start=0] The start of the range.
	     * @param {number} end The end of the range.
	     * @param {number} [step=1] The value to increment or decrement by.
	     * @returns {Array} Returns the new array of numbers.
	     * @example
	     *
	     * _.range(4);
	     * // => [0, 1, 2, 3]
	     *
	     * _.range(1, 5);
	     * // => [1, 2, 3, 4]
	     *
	     * _.range(0, 20, 5);
	     * // => [0, 5, 10, 15]
	     *
	     * _.range(0, -4, -1);
	     * // => [0, -1, -2, -3]
	     *
	     * _.range(1, 4, 0);
	     * // => [1, 1, 1]
	     *
	     * _.range(0);
	     * // => []
	     */
	    function range(start, end, step) {
	      if (step && isIterateeCall(start, end, step)) {
	        end = step = null;
	      }
	      start = +start || 0;
	      step = step == null ? 1 : (+step || 0);

	      if (end == null) {
	        end = start;
	        start = 0;
	      } else {
	        end = +end || 0;
	      }
	      // Use `Array(length)` so engines like Chakra and V8 avoid slower modes.
	      // See https://youtu.be/XAqIpGU8ZZk#t=17m25s for more details.
	      var index = -1,
	          length = nativeMax(ceil((end - start) / (step || 1)), 0),
	          result = Array(length);

	      while (++index < length) {
	        result[index] = start;
	        start += step;
	      }
	      return result;
	    }

	    /**
	     * Invokes the iteratee function `n` times, returning an array of the results
	     * of each invocation. The `iteratee` is bound to `thisArg` and invoked with
	     * one argument; (index).
	     *
	     * @static
	     * @memberOf _
	     * @category Utility
	     * @param {number} n The number of times to invoke `iteratee`.
	     * @param {Function} [iteratee=_.identity] The function invoked per iteration.
	     * @param {*} [thisArg] The `this` binding of `iteratee`.
	     * @returns {Array} Returns the array of results.
	     * @example
	     *
	     * var diceRolls = _.times(3, _.partial(_.random, 1, 6, false));
	     * // => [3, 6, 4]
	     *
	     * _.times(3, function(n) { mage.castSpell(n); });
	     * // => invokes `mage.castSpell(n)` three times with `n` of `0`, `1`, and `2` respectively
	     *
	     * _.times(3, function(n) { this.cast(n); }, mage);
	     * // => also invokes `mage.castSpell(n)` three times
	     */
	    function times(n, iteratee, thisArg) {
	      n = +n;

	      // Exit early to avoid a JSC JIT bug in Safari 8
	      // where `Array(0)` is treated as `Array(1)`.
	      if (n < 1 || !nativeIsFinite(n)) {
	        return [];
	      }
	      var index = -1,
	          result = Array(nativeMin(n, MAX_ARRAY_LENGTH));

	      iteratee = bindCallback(iteratee, thisArg, 1);
	      while (++index < n) {
	        if (index < MAX_ARRAY_LENGTH) {
	          result[index] = iteratee(index);
	        } else {
	          iteratee(index);
	        }
	      }
	      return result;
	    }

	    /**
	     * Generates a unique ID. If `prefix` is provided the ID is appended to it.
	     *
	     * @static
	     * @memberOf _
	     * @category Utility
	     * @param {string} [prefix] The value to prefix the ID with.
	     * @returns {string} Returns the unique ID.
	     * @example
	     *
	     * _.uniqueId('contact_');
	     * // => 'contact_104'
	     *
	     * _.uniqueId();
	     * // => '105'
	     */
	    function uniqueId(prefix) {
	      var id = ++idCounter;
	      return baseToString(prefix) + id;
	    }

	    /*------------------------------------------------------------------------*/

	    // Ensure `new LodashWrapper` is an instance of `lodash`.
	    LodashWrapper.prototype = lodash.prototype;

	    // Add functions to the `Map` cache.
	    MapCache.prototype['delete'] = mapDelete;
	    MapCache.prototype.get = mapGet;
	    MapCache.prototype.has = mapHas;
	    MapCache.prototype.set = mapSet;

	    // Add functions to the `Set` cache.
	    SetCache.prototype.push = cachePush;

	    // Assign cache to `_.memoize`.
	    memoize.Cache = MapCache;

	    // Add functions that return wrapped values when chaining.
	    lodash.after = after;
	    lodash.ary = ary;
	    lodash.assign = assign;
	    lodash.at = at;
	    lodash.before = before;
	    lodash.bind = bind;
	    lodash.bindAll = bindAll;
	    lodash.bindKey = bindKey;
	    lodash.callback = callback;
	    lodash.chain = chain;
	    lodash.chunk = chunk;
	    lodash.compact = compact;
	    lodash.constant = constant;
	    lodash.countBy = countBy;
	    lodash.create = create;
	    lodash.curry = curry;
	    lodash.curryRight = curryRight;
	    lodash.debounce = debounce;
	    lodash.defaults = defaults;
	    lodash.defer = defer;
	    lodash.delay = delay;
	    lodash.difference = difference;
	    lodash.drop = drop;
	    lodash.dropRight = dropRight;
	    lodash.dropRightWhile = dropRightWhile;
	    lodash.dropWhile = dropWhile;
	    lodash.filter = filter;
	    lodash.flatten = flatten;
	    lodash.flattenDeep = flattenDeep;
	    lodash.flow = flow;
	    lodash.flowRight = flowRight;
	    lodash.forEach = forEach;
	    lodash.forEachRight = forEachRight;
	    lodash.forIn = forIn;
	    lodash.forInRight = forInRight;
	    lodash.forOwn = forOwn;
	    lodash.forOwnRight = forOwnRight;
	    lodash.functions = functions;
	    lodash.groupBy = groupBy;
	    lodash.indexBy = indexBy;
	    lodash.initial = initial;
	    lodash.intersection = intersection;
	    lodash.invert = invert;
	    lodash.invoke = invoke;
	    lodash.keys = keys;
	    lodash.keysIn = keysIn;
	    lodash.map = map;
	    lodash.mapValues = mapValues;
	    lodash.matches = matches;
	    lodash.memoize = memoize;
	    lodash.merge = merge;
	    lodash.mixin = mixin;
	    lodash.negate = negate;
	    lodash.omit = omit;
	    lodash.once = once;
	    lodash.pairs = pairs;
	    lodash.partial = partial;
	    lodash.partialRight = partialRight;
	    lodash.partition = partition;
	    lodash.pick = pick;
	    lodash.pluck = pluck;
	    lodash.property = property;
	    lodash.propertyOf = propertyOf;
	    lodash.pull = pull;
	    lodash.pullAt = pullAt;
	    lodash.range = range;
	    lodash.rearg = rearg;
	    lodash.reject = reject;
	    lodash.remove = remove;
	    lodash.rest = rest;
	    lodash.shuffle = shuffle;
	    lodash.slice = slice;
	    lodash.sortBy = sortBy;
	    lodash.sortByAll = sortByAll;
	    lodash.take = take;
	    lodash.takeRight = takeRight;
	    lodash.takeRightWhile = takeRightWhile;
	    lodash.takeWhile = takeWhile;
	    lodash.tap = tap;
	    lodash.throttle = throttle;
	    lodash.thru = thru;
	    lodash.times = times;
	    lodash.toArray = toArray;
	    lodash.toPlainObject = toPlainObject;
	    lodash.transform = transform;
	    lodash.union = union;
	    lodash.uniq = uniq;
	    lodash.unzip = unzip;
	    lodash.values = values;
	    lodash.valuesIn = valuesIn;
	    lodash.where = where;
	    lodash.without = without;
	    lodash.wrap = wrap;
	    lodash.xor = xor;
	    lodash.zip = zip;
	    lodash.zipObject = zipObject;

	    // Add aliases.
	    lodash.backflow = flowRight;
	    lodash.collect = map;
	    lodash.compose = flowRight;
	    lodash.each = forEach;
	    lodash.eachRight = forEachRight;
	    lodash.extend = assign;
	    lodash.iteratee = callback;
	    lodash.methods = functions;
	    lodash.object = zipObject;
	    lodash.select = filter;
	    lodash.tail = rest;
	    lodash.unique = uniq;

	    // Add functions to `lodash.prototype`.
	    mixin(lodash, lodash);

	    /*------------------------------------------------------------------------*/

	    // Add functions that return unwrapped values when chaining.
	    lodash.attempt = attempt;
	    lodash.camelCase = camelCase;
	    lodash.capitalize = capitalize;
	    lodash.clone = clone;
	    lodash.cloneDeep = cloneDeep;
	    lodash.deburr = deburr;
	    lodash.endsWith = endsWith;
	    lodash.escape = escape;
	    lodash.escapeRegExp = escapeRegExp;
	    lodash.every = every;
	    lodash.find = find;
	    lodash.findIndex = findIndex;
	    lodash.findKey = findKey;
	    lodash.findLast = findLast;
	    lodash.findLastIndex = findLastIndex;
	    lodash.findLastKey = findLastKey;
	    lodash.findWhere = findWhere;
	    lodash.first = first;
	    lodash.has = has;
	    lodash.identity = identity;
	    lodash.includes = includes;
	    lodash.indexOf = indexOf;
	    lodash.isArguments = isArguments;
	    lodash.isArray = isArray;
	    lodash.isBoolean = isBoolean;
	    lodash.isDate = isDate;
	    lodash.isElement = isElement;
	    lodash.isEmpty = isEmpty;
	    lodash.isEqual = isEqual;
	    lodash.isError = isError;
	    lodash.isFinite = isFinite;
	    lodash.isFunction = isFunction;
	    lodash.isMatch = isMatch;
	    lodash.isNaN = isNaN;
	    lodash.isNative = isNative;
	    lodash.isNull = isNull;
	    lodash.isNumber = isNumber;
	    lodash.isObject = isObject;
	    lodash.isPlainObject = isPlainObject;
	    lodash.isRegExp = isRegExp;
	    lodash.isString = isString;
	    lodash.isTypedArray = isTypedArray;
	    lodash.isUndefined = isUndefined;
	    lodash.kebabCase = kebabCase;
	    lodash.last = last;
	    lodash.lastIndexOf = lastIndexOf;
	    lodash.max = max;
	    lodash.min = min;
	    lodash.noConflict = noConflict;
	    lodash.noop = noop;
	    lodash.now = now;
	    lodash.pad = pad;
	    lodash.padLeft = padLeft;
	    lodash.padRight = padRight;
	    lodash.parseInt = parseInt;
	    lodash.random = random;
	    lodash.reduce = reduce;
	    lodash.reduceRight = reduceRight;
	    lodash.repeat = repeat;
	    lodash.result = result;
	    lodash.runInContext = runInContext;
	    lodash.size = size;
	    lodash.snakeCase = snakeCase;
	    lodash.some = some;
	    lodash.sortedIndex = sortedIndex;
	    lodash.sortedLastIndex = sortedLastIndex;
	    lodash.startCase = startCase;
	    lodash.startsWith = startsWith;
	    lodash.template = template;
	    lodash.trim = trim;
	    lodash.trimLeft = trimLeft;
	    lodash.trimRight = trimRight;
	    lodash.trunc = trunc;
	    lodash.unescape = unescape;
	    lodash.uniqueId = uniqueId;
	    lodash.words = words;

	    // Add aliases.
	    lodash.all = every;
	    lodash.any = some;
	    lodash.contains = includes;
	    lodash.detect = find;
	    lodash.foldl = reduce;
	    lodash.foldr = reduceRight;
	    lodash.head = first;
	    lodash.include = includes;
	    lodash.inject = reduce;

	    mixin(lodash, (function() {
	      var source = {};
	      baseForOwn(lodash, function(func, methodName) {
	        if (!lodash.prototype[methodName]) {
	          source[methodName] = func;
	        }
	      });
	      return source;
	    }()), false);

	    /*------------------------------------------------------------------------*/

	    // Add functions capable of returning wrapped and unwrapped values when chaining.
	    lodash.sample = sample;

	    lodash.prototype.sample = function(n) {
	      if (!this.__chain__ && n == null) {
	        return sample(this.value());
	      }
	      return this.thru(function(value) {
	        return sample(value, n);
	      });
	    };

	    /*------------------------------------------------------------------------*/

	    /**
	     * The semantic version number.
	     *
	     * @static
	     * @memberOf _
	     * @type string
	     */
	    lodash.VERSION = VERSION;

	    // Assign default placeholders.
	    arrayEach(['bind', 'bindKey', 'curry', 'curryRight', 'partial', 'partialRight'], function(methodName) {
	      lodash[methodName].placeholder = lodash;
	    });

	    // Add `LazyWrapper` methods that accept an `iteratee` value.
	    arrayEach(['filter', 'map', 'takeWhile'], function(methodName, index) {
	      var isFilter = index == LAZY_FILTER_FLAG;

	      LazyWrapper.prototype[methodName] = function(iteratee, thisArg) {
	        var result = this.clone(),
	            filtered = result.filtered,
	            iteratees = result.iteratees || (result.iteratees = []);

	        result.filtered = filtered || isFilter || (index == LAZY_WHILE_FLAG && result.dir < 0);
	        iteratees.push({ 'iteratee': getCallback(iteratee, thisArg, 3), 'type': index });
	        return result;
	      };
	    });

	    // Add `LazyWrapper` methods for `_.drop` and `_.take` variants.
	    arrayEach(['drop', 'take'], function(methodName, index) {
	      var countName = methodName + 'Count',
	          whileName = methodName + 'While';

	      LazyWrapper.prototype[methodName] = function(n) {
	        n = n == null ? 1 : nativeMax(+n || 0, 0);

	        var result = this.clone();
	        if (result.filtered) {
	          var value = result[countName];
	          result[countName] = index ? nativeMin(value, n) : (value + n);
	        } else {
	          var views = result.views || (result.views = []);
	          views.push({ 'size': n, 'type': methodName + (result.dir < 0 ? 'Right' : '') });
	        }
	        return result;
	      };

	      LazyWrapper.prototype[methodName + 'Right'] = function(n) {
	        return this.reverse()[methodName](n).reverse();
	      };

	      LazyWrapper.prototype[methodName + 'RightWhile'] = function(predicate, thisArg) {
	        return this.reverse()[whileName](predicate, thisArg).reverse();
	      };
	    });

	    // Add `LazyWrapper` methods for `_.first` and `_.last`.
	    arrayEach(['first', 'last'], function(methodName, index) {
	      var takeName = 'take' + (index ? 'Right': '');

	      LazyWrapper.prototype[methodName] = function() {
	        return this[takeName](1).value()[0];
	      };
	    });

	    // Add `LazyWrapper` methods for `_.initial` and `_.rest`.
	    arrayEach(['initial', 'rest'], function(methodName, index) {
	      var dropName = 'drop' + (index ? '' : 'Right');

	      LazyWrapper.prototype[methodName] = function() {
	        return this[dropName](1);
	      };
	    });

	    // Add `LazyWrapper` methods for `_.pluck` and `_.where`.
	    arrayEach(['pluck', 'where'], function(methodName, index) {
	      var operationName = index ? 'filter' : 'map',
	          createCallback = index ? baseMatches : baseProperty;

	      LazyWrapper.prototype[methodName] = function(value) {
	        return this[operationName](createCallback(index ? value : (value + '')));
	      };
	    });

	    LazyWrapper.prototype.dropWhile = function(iteratee, thisArg) {
	      var done,
	          lastIndex,
	          isRight = this.dir < 0;

	      iteratee = getCallback(iteratee, thisArg, 3);
	      return this.filter(function(value, index, array) {
	        done = done && (isRight ? index < lastIndex : index > lastIndex);
	        lastIndex = index;
	        return done || (done = !iteratee(value, index, array));
	      });
	    };

	    LazyWrapper.prototype.reject = function(iteratee, thisArg) {
	      iteratee = getCallback(iteratee, thisArg, 3);
	      return this.filter(function(value, index, array) {
	        return !iteratee(value, index, array);
	      });
	    };

	    LazyWrapper.prototype.slice = function(start, end) {
	      start = start == null ? 0 : (+start || 0);
	      var result = start < 0 ? this.takeRight(-start) : this.drop(start);

	      if (typeof end != 'undefined') {
	        end = (+end || 0);
	        result = end < 0 ? result.dropRight(-end) : result.take(end - start);
	      }
	      return result;
	    };

	    // Add `LazyWrapper` methods to `lodash.prototype`.
	    baseForOwn(LazyWrapper.prototype, function(func, methodName) {
	      var lodashFunc = lodash[methodName],
	          retUnwrapped = /^(?:first|last)$/.test(methodName);

	      lodash.prototype[methodName] = function() {
	        var value = this.__wrapped__,
	            args = arguments,
	            chainAll = this.__chain__,
	            isHybrid = !!this.__actions__.length,
	            isLazy = value instanceof LazyWrapper,
	            onlyLazy = isLazy && !isHybrid;

	        if (retUnwrapped && !chainAll) {
	          return onlyLazy
	            ? func.call(value)
	            : lodashFunc.call(lodash, this.value());
	        }
	        var interceptor = function(value) {
	          var otherArgs = [value];
	          push.apply(otherArgs, args);
	          return lodashFunc.apply(lodash, otherArgs);
	        };
	        if (isLazy || isArray(value)) {
	          var wrapper = onlyLazy ? value : new LazyWrapper(this),
	              result = func.apply(wrapper, args);

	          if (!retUnwrapped && (isHybrid || result.actions)) {
	            var actions = result.actions || (result.actions = []);
	            actions.push({ 'func': thru, 'args': [interceptor], 'thisArg': lodash });
	          }
	          return new LodashWrapper(result, chainAll);
	        }
	        return this.thru(interceptor);
	      };
	    });

	    // Add `Array.prototype` functions to `lodash.prototype`.
	    arrayEach(['concat', 'join', 'pop', 'push', 'shift', 'sort', 'splice', 'unshift'], function(methodName) {
	      var func = arrayProto[methodName],
	          chainName = /^(?:push|sort|unshift)$/.test(methodName) ? 'tap' : 'thru',
	          retUnwrapped = /^(?:join|pop|shift)$/.test(methodName);

	      lodash.prototype[methodName] = function() {
	        var args = arguments;
	        if (retUnwrapped && !this.__chain__) {
	          return func.apply(this.value(), args);
	        }
	        return this[chainName](function(value) {
	          return func.apply(value, args);
	        });
	      };
	    });

	    // Add functions to the lazy wrapper.
	    LazyWrapper.prototype.clone = lazyClone;
	    LazyWrapper.prototype.reverse = lazyReverse;
	    LazyWrapper.prototype.value = lazyValue;

	    // Add chaining functions to the lodash wrapper.
	    lodash.prototype.chain = wrapperChain;
	    lodash.prototype.reverse = wrapperReverse;
	    lodash.prototype.toString = wrapperToString;
	    lodash.prototype.toJSON = lodash.prototype.valueOf = lodash.prototype.value = wrapperValue;

	    // Add function aliases to the lodash wrapper.
	    lodash.prototype.collect = lodash.prototype.map;
	    lodash.prototype.head = lodash.prototype.first;
	    lodash.prototype.select = lodash.prototype.filter;
	    lodash.prototype.tail = lodash.prototype.rest;

	    return lodash;
	  }

	  /*--------------------------------------------------------------------------*/

	  // Export lodash.
	  var _ = runInContext();

	  // Some AMD build optimizers like r.js check for condition patterns like the following:
	  if (true) {
	    // Expose lodash to the global object when an AMD loader is present to avoid
	    // errors in cases where lodash is loaded by a script tag and not intended
	    // as an AMD module. See http://requirejs.org/docs/errors.html#mismatch for
	    // more details.
	    root._ = _;

	    // Define as an anonymous module so, through path mapping, it can be
	    // referenced as the "underscore" module.
	    !(__WEBPACK_AMD_DEFINE_RESULT__ = function() {
	      return _;
	    }.call(exports, __webpack_require__, exports, module), __WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
	  }
	  // Check for `exports` after `define` in case a build optimizer adds an `exports` object.
	  else if (freeExports && freeModule) {
	    // Export for Node.js or RingoJS.
	    if (moduleExports) {
	      (freeModule.exports = _)._ = _;
	    }
	    // Export for Narwhal or Rhino -require.
	    else {
	      freeExports._ = _;
	    }
	  }
	  else {
	    // Export for a browser or Rhino.
	    root._ = _;
	  }
	}.call(this));

	/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(15)(module), (function() { return this; }())))

/***/ },
/* 12 */
/***/ function(module, exports, __webpack_require__) {

	/* WEBPACK VAR INJECTION */(function(Buffer) {// Generated by CoffeeScript 1.7.1

	/*
	PDFReference - represents a reference to another object in the PDF object heirarchy
	By Devon Govett
	 */

	(function() {
	  var PDFObject, PDFReference, zlib,
	    __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };

	  zlib = __webpack_require__(45);

	  PDFReference = (function() {
	    function PDFReference(document, id, data) {
	      this.document = document;
	      this.id = id;
	      this.data = data != null ? data : {};
	      this.finalize = __bind(this.finalize, this);
	      this.gen = 0;
	      this.deflate = null;
	      this.compress = this.document.compress && !this.data.Filter;
	      this.uncompressedLength = 0;
	      this.chunks = [];
	    }

	    PDFReference.prototype.initDeflate = function() {
	      this.data.Filter = 'FlateDecode';
	      this.deflate = zlib.createDeflate();
	      this.deflate.on('data', (function(_this) {
	        return function(chunk) {
	          _this.chunks.push(chunk);
	          return _this.data.Length += chunk.length;
	        };
	      })(this));
	      return this.deflate.on('end', this.finalize);
	    };

	    PDFReference.prototype.write = function(chunk) {
	      var _base;
	      if (!Buffer.isBuffer(chunk)) {
	        chunk = new Buffer(chunk + '\n', 'binary');
	      }
	      this.uncompressedLength += chunk.length;
	      if ((_base = this.data).Length == null) {
	        _base.Length = 0;
	      }
	      if (this.compress) {
	        if (!this.deflate) {
	          this.initDeflate();
	        }
	        return this.deflate.write(chunk);
	      } else {
	        this.chunks.push(chunk);
	        return this.data.Length += chunk.length;
	      }
	    };

	    PDFReference.prototype.end = function(chunk) {
	      if (typeof chunk === 'string' || Buffer.isBuffer(chunk)) {
	        this.write(chunk);
	      }
	      if (this.deflate) {
	        return this.deflate.end();
	      } else {
	        return this.finalize();
	      }
	    };

	    PDFReference.prototype.finalize = function() {
	      var chunk, _i, _len, _ref;
	      this.offset = this.document._offset;
	      this.document._write("" + this.id + " " + this.gen + " obj");
	      this.document._write(PDFObject.convert(this.data));
	      if (this.chunks.length) {
	        this.document._write('stream');
	        _ref = this.chunks;
	        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
	          chunk = _ref[_i];
	          this.document._write(chunk);
	        }
	        this.chunks.length = 0;
	        this.document._write('\nendstream');
	      }
	      this.document._write('endobj');
	      return this.document._refEnd(this);
	    };

	    PDFReference.prototype.toString = function() {
	      return "" + this.id + " " + this.gen + " R";
	    };

	    return PDFReference;

	  })();

	  module.exports = PDFReference;

	  PDFObject = __webpack_require__(32);

	}).call(this);

	/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(4).Buffer))

/***/ },
/* 13 */
/***/ function(module, exports, __webpack_require__) {

	module.exports = function() { throw new Error("define cannot be used indirect"); };


/***/ },
/* 14 */
/***/ function(module, exports, __webpack_require__) {

	/* WEBPACK VAR INJECTION */(function(__webpack_amd_options__) {module.exports = __webpack_amd_options__;

	/* WEBPACK VAR INJECTION */}.call(exports, {}))

/***/ },
/* 15 */
/***/ function(module, exports, __webpack_require__) {

	module.exports = function(module) {
		if(!module.webpackPolyfill) {
			module.deprecate = function() {};
			module.paths = [];
			// module.parent = undefined by default
			module.children = [];
			module.webpackPolyfill = 1;
		}
		return module;
	}


/***/ },
/* 16 */
/***/ function(module, exports, __webpack_require__) {

	/* jslint node: true */
	'use strict';

	var _ = __webpack_require__(11);

	function FontWrapper(pdfkitDoc, path, fontName){
		this.MAX_CHAR_TYPES = 92;

		this.pdfkitDoc = pdfkitDoc;
		this.path = path;
		this.pdfFonts = [];
		this.charCatalogue = [];
		this.name = fontName;

	  this.__defineGetter__('ascender', function(){
	    var font = this.getFont(0);
	    return font.ascender;
	  });
	  this.__defineGetter__('decender', function(){
	    var font = this.getFont(0);
	    return font.decender;
	  });

	}
	// private

	FontWrapper.prototype.getFont = function(index){
		if(!this.pdfFonts[index]){

			var pseudoName = this.name + index;

			if(this.postscriptName){
				delete this.pdfkitDoc._fontFamilies[this.postscriptName];
			}

			this.pdfFonts[index] = this.pdfkitDoc.font(this.path, pseudoName)._font;
			if(!this.postscriptName){
				this.postscriptName = this.pdfFonts[index].name;
			}
		}

		return this.pdfFonts[index];
	};

	// public
	FontWrapper.prototype.widthOfString = function(){
		var font = this.getFont(0);
		return font.widthOfString.apply(font, arguments);
	};

	FontWrapper.prototype.lineHeight = function(){
		var font = this.getFont(0);
		return font.lineHeight.apply(font, arguments);
	};

	FontWrapper.prototype.ref = function(){
		var font = this.getFont(0);
		return font.ref.apply(font, arguments);
	};

	var toCharCode = function(char){
	  return char.charCodeAt(0);
	};

	FontWrapper.prototype.encode = function(text){
	  var self = this;

	  var charTypesInInline = _.chain(text.split('')).map(toCharCode).uniq().value();
		if (charTypesInInline.length > self.MAX_CHAR_TYPES) {
			throw new Error('Inline has more than '+ self.MAX_CHAR_TYPES + ': ' + text + ' different character types and therefore cannot be properly embedded into pdf.');
		}


	  var characterFitInFontWithIndex = function (charCatalogue) {
	    return _.uniq(charCatalogue.concat(charTypesInInline)).length <= self.MAX_CHAR_TYPES;
	  };

	  var index = _.findIndex(self.charCatalogue, characterFitInFontWithIndex);

	  if(index < 0){
	    index = self.charCatalogue.length;
	    self.charCatalogue[index] = [];
	  }

		var font = this.getFont(index);
		font.use(text);

	  _.each(charTypesInInline, function(charCode){
	    if(!_.includes(self.charCatalogue[index], charCode)){
	      self.charCatalogue[index].push(charCode);
	    }
	  });

	  var encodedText = _.map(font.encode(text), function (char) {
	    return char.charCodeAt(0).toString(16);
	  }).join('');

	  return {
	    encodedText: encodedText,
	    fontId: font.id
	  };
	};


	module.exports = FontWrapper;


/***/ },
/* 17 */
/***/ function(module, exports, __webpack_require__) {

	/* WEBPACK VAR INJECTION */(function(Buffer) {// Generated by CoffeeScript 1.7.1

	/*
	PDFImage - embeds images in PDF documents
	By Devon Govett
	 */

	(function() {
	  var Data, JPEG, PDFImage, PNG, fs;

	  fs = __webpack_require__(10);

	  Data = __webpack_require__(34);

	  JPEG = __webpack_require__(35);

	  PNG = __webpack_require__(36);

	  PDFImage = (function() {
	    function PDFImage() {}

	    PDFImage.open = function(src, label) {
	      var data, match;
	      if (Buffer.isBuffer(src)) {
	        data = src;
	      } else {
	        if (match = /^data:.+;base64,(.*)$/.exec(src)) {
	          data = new Buffer(match[1], 'base64');
	        } else {
	          data = fs.readFileSync(src);
	          if (!data) {
	            return;
	          }
	        }
	      }
	      if (data[0] === 0xff && data[1] === 0xd8) {
	        return new JPEG(data, label);
	      } else if (data[0] === 0x89 && data.toString('ascii', 1, 4) === 'PNG') {
	        return new PNG(data, label);
	      } else {
	        throw new Error('Unknown image format.');
	      }
	    };

	    return PDFImage;

	  })();

	  module.exports = PDFImage;

	}).call(this);

	/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(4).Buffer))

/***/ },
/* 18 */
/***/ function(module, exports, __webpack_require__) {

	/* jslint node: true */
	'use strict';

	/**
	* Creates an instance of TraversalTracker
	*
	* @constructor
	*/
	function TraversalTracker() {
		this.events = {};
	}

	TraversalTracker.prototype.startTracking = function(event, cb) {
		var callbacks = (this.events[event] || (this.events[event] = []));

		if (callbacks.indexOf(cb) < 0) {
			callbacks.push(cb);
		}
	};

	TraversalTracker.prototype.stopTracking = function(event, cb) {
		var callbacks = this.events[event];

		if (callbacks) {
			var index = callbacks.indexOf(cb);
			if (index >= 0) {
				callbacks.splice(index, 1);
			}
		}
	};

	TraversalTracker.prototype.emit = function(event) {
		var args = Array.prototype.slice.call(arguments, 1);

		var callbacks = this.events[event];

		if (callbacks) {
			callbacks.forEach(function(cb) {
				cb.apply(this, args);
			});
		}
	};

	TraversalTracker.prototype.auto = function(event, cb, innerBlock) {
		this.startTracking(event, cb);
		innerBlock();
		this.stopTracking(event, cb);
	};

	module.exports = TraversalTracker;


/***/ },
/* 19 */
/***/ function(module, exports, __webpack_require__) {

	/* jslint node: true */
	'use strict';

	var TextTools = __webpack_require__(26);
	var StyleContextStack = __webpack_require__(27);
	var ColumnCalculator = __webpack_require__(22);
	var fontStringify = __webpack_require__(25).fontStringify;
	var pack = __webpack_require__(25).pack;
	var qrEncoder = __webpack_require__(33);

	/**
	* @private
	*/
	function DocMeasure(fontProvider, styleDictionary, defaultStyle, imageMeasure, tableLayouts, images) {
		this.textTools = new TextTools(fontProvider);
		this.styleStack = new StyleContextStack(styleDictionary, defaultStyle);
		this.imageMeasure = imageMeasure;
		this.tableLayouts = tableLayouts;
		this.images = images;
		this.autoImageIndex = 1;
	}

	/**
	* Measures all nodes and sets min/max-width properties required for the second
	* layout-pass.
	* @param  {Object} docStructure document-definition-object
	* @return {Object}              document-measurement-object
	*/
	DocMeasure.prototype.measureDocument = function(docStructure) {
		return this.measureNode(docStructure);
	};

	DocMeasure.prototype.measureNode = function(node) {
		// expand shortcuts
		if (node instanceof Array) {
			node = { stack: node };
		} else if (typeof node == 'string' || node instanceof String) {
			node = { text: node };
		}

		var self = this;

		return this.styleStack.auto(node, function() {
			// TODO: refactor + rethink whether this is the proper way to handle margins
			node._margin = getNodeMargin(node);

			if (node.columns) {
				return extendMargins(self.measureColumns(node));
			} else if (node.stack) {
				return extendMargins(self.measureVerticalContainer(node));
			} else if (node.ul) {
				return extendMargins(self.measureList(false, node));
			} else if (node.ol) {
				return extendMargins(self.measureList(true, node));
			} else if (node.table) {
				return extendMargins(self.measureTable(node));
			} else if (node.text !== undefined) {
				return extendMargins(self.measureLeaf(node));
			} else if (node.image) {
				return extendMargins(self.measureImage(node));
			} else if (node.canvas) {
				return extendMargins(self.measureCanvas(node));
			} else if (node.qr) {
				return extendMargins(self.measureQr(node));
			} else {
				throw 'Unrecognized document structure: ' + JSON.stringify(node, fontStringify);
			}
		});

		function extendMargins(node) {
			var margin = node._margin;

			if (margin) {
				node._minWidth += margin[0] + margin[2];
				node._maxWidth += margin[0] + margin[2];
			}

			return node;
		}

		function getNodeMargin() {

			function processSingleMargins(node, currentMargin){
				if (node.marginLeft || node.marginTop || node.marginRight || node.marginBottom) {
					return [
						node.marginLeft || currentMargin[0] || 0,
						node.marginTop || currentMargin[1] || 0,
						node.marginRight || currentMargin[2]  || 0,
						node.marginBottom || currentMargin[3]  || 0
					];
				}
				return currentMargin;
			}

			function flattenStyleArray(styleArray){
				var flattenedStyles = {};
				for (var i = styleArray.length - 1; i >= 0; i--) {
					var styleName = styleArray[i];
					var style = self.styleStack.styleDictionary[styleName];
					for(var key in style){
						if(style.hasOwnProperty(key)){
							flattenedStyles[key] = style[key];
						}
					}
				}
				return flattenedStyles;
			}

			function convertMargin(margin) {
				if (typeof margin === 'number' || margin instanceof Number) {
					margin = [ margin, margin, margin, margin ];
				} else if (margin instanceof Array) {
					if (margin.length === 2) {
						margin = [ margin[0], margin[1], margin[0], margin[1] ];
					}
				}
				return margin;
			}

			var margin = [undefined, undefined, undefined, undefined];

			if(node.style) {
				var styleArray = (node.style instanceof Array) ? node.style : [node.style];
				var flattenedStyleArray = flattenStyleArray(styleArray);

				if(flattenedStyleArray) {
					margin = processSingleMargins(flattenedStyleArray, margin);
				}

				if(flattenedStyleArray.margin){
					margin = convertMargin(flattenedStyleArray.margin);
				}
			}
			
			margin = processSingleMargins(node, margin);

			if(node.margin){
				margin = convertMargin(node.margin);
			}

			if(margin[0] === undefined && margin[1] === undefined && margin[2] === undefined && margin[3] === undefined) {
				return null;
			} else {
				return margin;
			}
		}
	};

	DocMeasure.prototype.convertIfBase64Image = function(node) {
		if (/^data:image\/(jpeg|jpg|png);base64,/.test(node.image)) {
			var label = '$$pdfmake$$' + this.autoImageIndex++;
			this.images[label] = node.image;
			node.image = label;
	}
	};

	DocMeasure.prototype.measureImage = function(node) {
		if (this.images) {
			this.convertIfBase64Image(node);
		}

		var imageSize = this.imageMeasure.measureImage(node.image);

		if (node.fit) {
			var factor = (imageSize.width / imageSize.height > node.fit[0] / node.fit[1]) ? node.fit[0] / imageSize.width : node.fit[1] / imageSize.height;
			node._width = node._minWidth = node._maxWidth = imageSize.width * factor;
			node._height = imageSize.height * factor;
		} else {
			node._width = node._minWidth = node._maxWidth = node.width || imageSize.width;
			node._height = node.height || (imageSize.height * node._width / imageSize.width);
		}

		node._alignment = this.styleStack.getProperty('alignment');
		return node;
	};

	DocMeasure.prototype.measureLeaf = function(node) {
		var data = this.textTools.buildInlines(node.text, this.styleStack);

		node._inlines = data.items;
		node._minWidth = data.minWidth;
		node._maxWidth = data.maxWidth;

		return node;
	};

	DocMeasure.prototype.measureVerticalContainer = function(node) {
		var items = node.stack;

		node._minWidth = 0;
		node._maxWidth = 0;

		for(var i = 0, l = items.length; i < l; i++) {
			items[i] = this.measureNode(items[i]);

			node._minWidth = Math.max(node._minWidth, items[i]._minWidth);
			node._maxWidth = Math.max(node._maxWidth, items[i]._maxWidth);
		}

		return node;
	};

	DocMeasure.prototype.gapSizeForList = function(isOrderedList, listItems) {
		if (isOrderedList) {
			var longestNo = (listItems.length).toString().replace(/./g, '9');
			return this.textTools.sizeOfString(longestNo + '. ', this.styleStack);
		} else {
			return this.textTools.sizeOfString('9. ', this.styleStack);
		}
	};

	DocMeasure.prototype.buildMarker = function(isOrderedList, counter, styleStack, gapSize) {
		var marker;

		if (isOrderedList) {
			marker = { _inlines: this.textTools.buildInlines(counter, styleStack).items };
		}
		else {
			// TODO: ascender-based calculations
			var radius = gapSize.fontSize / 6;
			marker = {
				canvas: [ {
					x: radius,
					y: (gapSize.height / gapSize.lineHeight) + gapSize.decender - gapSize.fontSize / 3,//0,// gapSize.fontSize * 2 / 3,
					r1: radius,
					r2: radius,
					type: 'ellipse',
					color: 'black'
				} ]
			};
		}

		marker._minWidth = marker._maxWidth = gapSize.width;
		marker._minHeight = marker._maxHeight = gapSize.height;

		return marker;
	};

	DocMeasure.prototype.measureList = function(isOrdered, node) {
		var style = this.styleStack.clone();

		var items = isOrdered ? node.ol : node.ul;
		node._gapSize = this.gapSizeForList(isOrdered, items);
		node._minWidth = 0;
		node._maxWidth = 0;

		var counter = 1;

		for(var i = 0, l = items.length; i < l; i++) {
			var nextItem = items[i] = this.measureNode(items[i]);

			var marker = counter++ + '. ';

			if (!nextItem.ol && !nextItem.ul) {
				nextItem.listMarker = this.buildMarker(isOrdered, nextItem.counter || marker, style, node._gapSize);
			}  // TODO: else - nested lists numbering

			node._minWidth = Math.max(node._minWidth, items[i]._minWidth + node._gapSize.width);
			node._maxWidth = Math.max(node._maxWidth, items[i]._maxWidth + node._gapSize.width);
		}

		return node;
	};

	DocMeasure.prototype.measureColumns = function(node) {
		var columns = node.columns;
		node._gap = this.styleStack.getProperty('columnGap') || 0;

		for(var i = 0, l = columns.length; i < l; i++) {
			columns[i] = this.measureNode(columns[i]);
		}

		var measures = ColumnCalculator.measureMinMax(columns);

		node._minWidth = measures.min + node._gap * (columns.length - 1);
		node._maxWidth = measures.max + node._gap * (columns.length - 1);

		return node;
	};

	DocMeasure.prototype.measureTable = function(node) {
		extendTableWidths(node);
		node._layout = getLayout(this.tableLayouts);
		node._offsets = getOffsets(node._layout);

		var colSpans = [];
		var col, row, cols, rows;

		for(col = 0, cols = node.table.body[0].length; col < cols; col++) {
			var c = node.table.widths[col];
			c._minWidth = 0;
			c._maxWidth = 0;

			for(row = 0, rows = node.table.body.length; row < rows; row++) {
				var rowData = node.table.body[row];
				var data = rowData[col];
				if (!data._span) {
					var _this = this;
					data = rowData[col] = this.styleStack.auto(data, measureCb(this, data));

					if (data.colSpan && data.colSpan > 1) {
						markSpans(rowData, col, data.colSpan);
						colSpans.push({ col: col, span: data.colSpan, minWidth: data._minWidth, maxWidth: data._maxWidth });
					} else {
						c._minWidth = Math.max(c._minWidth, data._minWidth);
						c._maxWidth = Math.max(c._maxWidth, data._maxWidth);
					}
				}

				if (data.rowSpan && data.rowSpan > 1) {
					markVSpans(node.table, row, col, data.rowSpan);
				}
			}
		}

		extendWidthsForColSpans();

		var measures = ColumnCalculator.measureMinMax(node.table.widths);

		node._minWidth = measures.min + node._offsets.total;
		node._maxWidth = measures.max + node._offsets.total;

		return node;

		function measureCb(_this, data) {
			return function() {
				if (data !== null && typeof data === 'object') {
					data.fillColor = _this.styleStack.getProperty('fillColor');
				}
				return _this.measureNode(data);
			};
		}

		function getLayout(tableLayouts) {
			var layout = node.layout;

			if (typeof node.layout === 'string' || node instanceof String) {
				layout = tableLayouts[layout];
			}

			var defaultLayout = {
				hLineWidth: function(i, node) { return 1; }, //return node.table.headerRows && i === node.table.headerRows && 3 || 0; },
				vLineWidth: function(i, node) { return 1; },
				hLineColor: function(i, node) { return 'black'; },
				vLineColor: function(i, node) { return 'black'; },
				paddingLeft: function(i, node) { return 4; }, //i && 4 || 0; },
				paddingRight: function(i, node) { return 4; }, //(i < node.table.widths.length - 1) ? 4 : 0; },
				paddingTop: function(i, node) { return 2; },
				paddingBottom: function(i, node) { return 2; }
			};

			return pack(defaultLayout, layout);
		}

		function getOffsets(layout) {
			var offsets = [];
			var totalOffset = 0;
			var prevRightPadding = 0;

			for(var i = 0, l = node.table.widths.length; i < l; i++) {
				var lOffset = prevRightPadding + layout.vLineWidth(i, node) + layout.paddingLeft(i, node);
				offsets.push(lOffset);
				totalOffset += lOffset;
				prevRightPadding = layout.paddingRight(i, node);
			}

			totalOffset += prevRightPadding + layout.vLineWidth(node.table.widths.length, node);

			return {
				total: totalOffset,
				offsets: offsets
			};
		}

		function extendWidthsForColSpans() {
			var q, j;

			for (var i = 0, l = colSpans.length; i < l; i++) {
				var span = colSpans[i];

				var currentMinMax = getMinMax(span.col, span.span, node._offsets);
				var minDifference = span.minWidth - currentMinMax.minWidth;
				var maxDifference = span.maxWidth - currentMinMax.maxWidth;

				if (minDifference > 0) {
					q = minDifference / span.span;

					for(j = 0; j < span.span; j++) {
						node.table.widths[span.col + j]._minWidth += q;
					}
				}

				if (maxDifference > 0) {
					q = maxDifference / span.span;

					for(j = 0; j < span.span; j++) {
						node.table.widths[span.col + j]._maxWidth += q;
					}
				}
			}
		}

		function getMinMax(col, span, offsets) {
			var result = { minWidth: 0, maxWidth: 0 };

			for(var i = 0; i < span; i++) {
				result.minWidth += node.table.widths[col + i]._minWidth + (i? offsets.offsets[col + i] : 0);
				result.maxWidth += node.table.widths[col + i]._maxWidth + (i? offsets.offsets[col + i] : 0);
			}

			return result;
		}

		function markSpans(rowData, col, span) {
			for (var i = 1; i < span; i++) {
				rowData[col + i] = {
					_span: true,
					_minWidth: 0,
					_maxWidth: 0,
					rowSpan: rowData[col].rowSpan
				};
			}
		}

		function markVSpans(table, row, col, span) {
			for (var i = 1; i < span; i++) {
				table.body[row + i][col] = {
					_span: true,
					_minWidth: 0,
					_maxWidth: 0,
					fillColor: table.body[row][col].fillColor
				};
			}
		}

		function extendTableWidths(node) {
			if (!node.table.widths) {
				node.table.widths = 'auto';
			}

			if (typeof node.table.widths === 'string' || node.table.widths instanceof String) {
				node.table.widths = [ node.table.widths ];

				while(node.table.widths.length < node.table.body[0].length) {
					node.table.widths.push(node.table.widths[node.table.widths.length - 1]);
				}
			}

			for(var i = 0, l = node.table.widths.length; i < l; i++) {
				var w = node.table.widths[i];
				if (typeof w === 'number' || w instanceof Number || typeof w === 'string' || w instanceof String) {
					node.table.widths[i] = { width: w };
				}
			}
		}
	};

	DocMeasure.prototype.measureCanvas = function(node) {
		var w = 0, h = 0;

		for(var i = 0, l = node.canvas.length; i < l; i++) {
			var vector = node.canvas[i];

			switch(vector.type) {
			case 'ellipse':
				w = Math.max(w, vector.x + vector.r1);
				h = Math.max(h, vector.y + vector.r2);
				break;
			case 'rect':
				w = Math.max(w, vector.x + vector.w);
				h = Math.max(h, vector.y + vector.h);
				break;
			case 'line':
				w = Math.max(w, vector.x1, vector.x2);
				h = Math.max(h, vector.y1, vector.y2);
				break;
			case 'polyline':
				for(var i2 = 0, l2 = vector.points.length; i2 < l2; i2++) {
					w = Math.max(w, vector.points[i2].x);
					h = Math.max(h, vector.points[i2].y);
				}
				break;
			}
		}

		node._minWidth = node._maxWidth = w;
		node._minHeight = node._maxHeight = h;

		return node;
	};

	DocMeasure.prototype.measureQr = function(node) {
		node = qrEncoder.measure(node);
		node._alignment = this.styleStack.getProperty('alignment');
		return node;
	};

	module.exports = DocMeasure;


/***/ },
/* 20 */
/***/ function(module, exports, __webpack_require__) {

	/* jslint node: true */
	'use strict';

	var TraversalTracker = __webpack_require__(18);

	/**
	* Creates an instance of DocumentContext - a store for current x, y positions and available width/height.
	* It facilitates column divisions and vertical sync
	*/
	function DocumentContext(pageSize, pageMargins) {
		this.pages = [];

		this.pageMargins = pageMargins;

		this.x = pageMargins.left;
		this.availableWidth = pageSize.width - pageMargins.left - pageMargins.right;
		this.availableHeight = 0;
		this.page = -1;

		this.snapshots = [];

		this.endingCell = null;

	  this.tracker = new TraversalTracker();

		this.addPage(pageSize);
	}

	DocumentContext.prototype.beginColumnGroup = function() {
		this.snapshots.push({
			x: this.x,
			y: this.y,
			availableHeight: this.availableHeight,
			availableWidth: this.availableWidth,
			page: this.page,
			bottomMost: { y: this.y, page: this.page },
			endingCell: this.endingCell,
			lastColumnWidth: this.lastColumnWidth
		});

		this.lastColumnWidth = 0;
	};

	DocumentContext.prototype.beginColumn = function(width, offset, endingCell) {
		var saved = this.snapshots[this.snapshots.length - 1];

		this.calculateBottomMost(saved);

	  this.endingCell = endingCell;
		this.page = saved.page;
		this.x = this.x + this.lastColumnWidth + (offset || 0);
		this.y = saved.y;
		this.availableWidth = width;	//saved.availableWidth - offset;
		this.availableHeight = saved.availableHeight;

		this.lastColumnWidth = width;
	};

	DocumentContext.prototype.calculateBottomMost = function(destContext) {
		if (this.endingCell) {
			this.saveContextInEndingCell(this.endingCell);
			this.endingCell = null;
		} else {
			destContext.bottomMost = bottomMostContext(this, destContext.bottomMost);
		}
	};

	DocumentContext.prototype.markEnding = function(endingCell) {
		this.page = endingCell._columnEndingContext.page;
		this.x = endingCell._columnEndingContext.x;
		this.y = endingCell._columnEndingContext.y;
		this.availableWidth = endingCell._columnEndingContext.availableWidth;
		this.availableHeight = endingCell._columnEndingContext.availableHeight;
		this.lastColumnWidth = endingCell._columnEndingContext.lastColumnWidth;
	};

	DocumentContext.prototype.saveContextInEndingCell = function(endingCell) {
		endingCell._columnEndingContext = {
			page: this.page,
			x: this.x,
			y: this.y,
			availableHeight: this.availableHeight,
			availableWidth: this.availableWidth,
			lastColumnWidth: this.lastColumnWidth
		};
	};

	DocumentContext.prototype.completeColumnGroup = function() {
		var saved = this.snapshots.pop();

		this.calculateBottomMost(saved);

		this.endingCell = null;
		this.x = saved.x;
		this.y = saved.bottomMost.y;
		this.page = saved.bottomMost.page;
		this.availableWidth = saved.availableWidth;
		this.availableHeight = saved.bottomMost.availableHeight;
		this.lastColumnWidth = saved.lastColumnWidth;
	};

	DocumentContext.prototype.addMargin = function(left, right) {
		this.x += left;
		this.availableWidth -= left + (right || 0);
	};

	DocumentContext.prototype.moveDown = function(offset) {
		this.y += offset;
		this.availableHeight -= offset;

		return this.availableHeight > 0;
	};

	DocumentContext.prototype.initializePage = function() {
		this.y = this.pageMargins.top;
		this.availableHeight = this.getCurrentPage().pageSize.height - this.pageMargins.top - this.pageMargins.bottom;
		this.pageSnapshot().availableWidth = this.getCurrentPage().pageSize.width - this.pageMargins.left - this.pageMargins.right;
	};

	DocumentContext.prototype.pageSnapshot = function(){
	  if(this.snapshots[0]){
	    return this.snapshots[0];
	  } else {
	    return this;
	  }
	};

	DocumentContext.prototype.moveTo = function(x,y) {
		if(x !== undefined && x !== null) {
			this.x = x;
			this.availableWidth = this.getCurrentPage().pageSize.width - this.x - this.pageMargins.right;
		}
		if(y !== undefined && y !== null){
			this.y = y;
			this.availableHeight = this.getCurrentPage().pageSize.height - this.y - this.pageMargins.bottom;
		}
	};

	DocumentContext.prototype.beginDetachedBlock = function() {
		this.snapshots.push({
			x: this.x,
			y: this.y,
			availableHeight: this.availableHeight,
			availableWidth: this.availableWidth,
			page: this.page,
			endingCell: this.endingCell,
			lastColumnWidth: this.lastColumnWidth
		});
	};

	DocumentContext.prototype.endDetachedBlock = function() {
		var saved = this.snapshots.pop();

		this.x = saved.x;
		this.y = saved.y;
		this.availableWidth = saved.availableWidth;
		this.availableHeight = saved.availableHeight;
		this.page = saved.page;
		this.endingCell = saved.endingCell;
		this.lastColumnWidth = saved.lastColumnWidth;
	};

	function pageOrientation(pageOrientationString, currentPageOrientation){
		if(pageOrientationString === undefined) {
			return currentPageOrientation;
		} else if(pageOrientationString === 'landscape'){
			return 'landscape';
		} else {
			return 'portrait';
		}
	}

	var getPageSize = function (currentPage, newPageOrientation) {

		newPageOrientation = pageOrientation(newPageOrientation, currentPage.pageSize.orientation);

		if(newPageOrientation !== currentPage.pageSize.orientation) {
			return {
				orientation: newPageOrientation,
				width: currentPage.pageSize.height,
				height: currentPage.pageSize.width
			};
		} else {
			return {
				orientation: currentPage.pageSize.orientation,
				width: currentPage.pageSize.width,
				height: currentPage.pageSize.height
			};
		}

	};


	DocumentContext.prototype.moveToNextPage = function(pageOrientation) {
		var nextPageIndex = this.page + 1;

		var prevPage = this.page;
		var prevY = this.y;

		var createNewPage = nextPageIndex >= this.pages.length;
		if (createNewPage) {
			this.addPage(getPageSize(this.getCurrentPage(), pageOrientation));
		} else {
			this.page = nextPageIndex;
			this.initializePage();
		}

	  return {
			newPageCreated: createNewPage,
			prevPage: prevPage,
			prevY: prevY,
			y: this.y
		};
	};


	DocumentContext.prototype.addPage = function(pageSize) {
		var page = { items: [], pageSize: pageSize };
		this.pages.push(page);
		this.page = this.pages.length - 1;
		this.initializePage();

		this.tracker.emit('pageAdded');

		return page;
	};

	DocumentContext.prototype.getCurrentPage = function() {
		if (this.page < 0 || this.page >= this.pages.length) return null;

		return this.pages[this.page];
	};

	DocumentContext.prototype.getCurrentPosition = function() {
	  var pageSize = this.getCurrentPage().pageSize;
	  var innerHeight = pageSize.height - this.pageMargins.top - this.pageMargins.bottom;
	  var innerWidth = pageSize.width - this.pageMargins.left - this.pageMargins.right;

	  return {
	    pageNumber: this.page + 1,
	    pageOrientation: pageSize.orientation,
	    pageInnerHeight: innerHeight,
	    pageInnerWidth: innerWidth,
	    left: this.x,
	    top: this.y,
	    verticalRatio: ((this.y - this.pageMargins.top) / innerHeight),
	    horizontalRatio: ((this.x - this.pageMargins.left) / innerWidth)
	  };
	};

	function bottomMostContext(c1, c2) {
		var r;

		if (c1.page > c2.page) r = c1;
		else if (c2.page > c1.page) r = c2;
		else r = (c1.y > c2.y) ? c1 : c2;

		return {
			page: r.page,
			x: r.x,
			y: r.y,
			availableHeight: r.availableHeight,
			availableWidth: r.availableWidth
		};
	}

	/****TESTS**** (add a leading '/' to uncomment)
	DocumentContext.bottomMostContext = bottomMostContext;
	// */

	module.exports = DocumentContext;


/***/ },
/* 21 */
/***/ function(module, exports, __webpack_require__) {

	/* jslint node: true */
	'use strict';

	var ElementWriter = __webpack_require__(37);

	/**
	* Creates an instance of PageElementWriter - an extended ElementWriter
	* which can handle:
	* - page-breaks (it adds new pages when there's not enough space left),
	* - repeatable fragments (like table-headers, which are repeated everytime
	*                         a page-break occurs)
	* - transactions (used for unbreakable-blocks when we want to make sure
	*                 whole block will be rendered on the same page)
	*/
	function PageElementWriter(context, tracker) {
		this.transactionLevel = 0;
		this.repeatables = [];
		this.tracker = tracker;
		this.writer = new ElementWriter(context, tracker);
	}

	function fitOnPage(self, addFct){
	  var position = addFct(self);
	  if (!position) {
	    self.moveToNextPage();
	    position = addFct(self);
	  }
	  return position;
	}

	PageElementWriter.prototype.addLine = function(line, dontUpdateContextPosition, index) {
	  return fitOnPage(this, function(self){
	    return self.writer.addLine(line, dontUpdateContextPosition, index);
	  });
	};

	PageElementWriter.prototype.addImage = function(image, index) {
	  return fitOnPage(this, function(self){
	    return self.writer.addImage(image, index);
	  });
	};

	PageElementWriter.prototype.addQr = function(qr, index) {
	  return fitOnPage(this, function(self){
			return self.writer.addQr(qr, index);
		});
	};

	PageElementWriter.prototype.addVector = function(vector, ignoreContextX, ignoreContextY, index) {
		return this.writer.addVector(vector, ignoreContextX, ignoreContextY, index);
	};

	PageElementWriter.prototype.addFragment = function(fragment, useBlockXOffset, useBlockYOffset, dontUpdateContextPosition) {
		if (!this.writer.addFragment(fragment, useBlockXOffset, useBlockYOffset, dontUpdateContextPosition)) {
			this.moveToNextPage();
			this.writer.addFragment(fragment, useBlockXOffset, useBlockYOffset, dontUpdateContextPosition);
		}
	};

	PageElementWriter.prototype.moveToNextPage = function(pageOrientation) {
		
		var nextPage = this.writer.context.moveToNextPage(pageOrientation);
		
	  if (nextPage.newPageCreated) {
			this.repeatables.forEach(function(rep) {
				this.writer.addFragment(rep, true);
			}, this);
		} else {
			this.repeatables.forEach(function(rep) {
				this.writer.context.moveDown(rep.height);
			}, this);
		}

		this.writer.tracker.emit('pageChanged', {
			prevPage: nextPage.prevPage,
			prevY: nextPage.prevY,
			y: nextPage.y
		});
	};

	PageElementWriter.prototype.beginUnbreakableBlock = function(width, height) {
		if (this.transactionLevel++ === 0) {
			this.originalX = this.writer.context.x;
			this.writer.pushContext(width, height);
		}
	};

	PageElementWriter.prototype.commitUnbreakableBlock = function(forcedX, forcedY) {
		if (--this.transactionLevel === 0) {
			var unbreakableContext = this.writer.context;
			this.writer.popContext();

			var nbPages = unbreakableContext.pages.length;
			if(nbPages > 0) {
				// no support for multi-page unbreakableBlocks
				var fragment = unbreakableContext.pages[0];
				fragment.xOffset = forcedX;
				fragment.yOffset = forcedY;

				//TODO: vectors can influence height in some situations
				if(nbPages > 1) {
					// on out-of-context blocs (headers, footers, background) height should be the whole DocumentContext height
					if (forcedX !== undefined || forcedY !== undefined) {
						fragment.height = unbreakableContext.getCurrentPage().pageSize.height - unbreakableContext.pageMargins.top - unbreakableContext.pageMargins.bottom;
					} else {
						fragment.height = this.writer.context.getCurrentPage().pageSize.height - this.writer.context.pageMargins.top - this.writer.context.pageMargins.bottom;
						for (var i = 0, l = this.repeatables.length; i < l; i++) {
							fragment.height -= this.repeatables[i].height;
						}
					}
				} else {
					fragment.height = unbreakableContext.y;
				}

				if (forcedX !== undefined || forcedY !== undefined) {
					this.writer.addFragment(fragment, true, true, true);
				} else {
					this.addFragment(fragment);
				}
			}
		}
	};

	PageElementWriter.prototype.currentBlockToRepeatable = function() {
		var unbreakableContext = this.writer.context;
		var rep = { items: [] };

	    unbreakableContext.pages[0].items.forEach(function(item) {
	        rep.items.push(item);
	    });

		rep.xOffset = this.originalX;

		//TODO: vectors can influence height in some situations
		rep.height = unbreakableContext.y;

		return rep;
	};

	PageElementWriter.prototype.pushToRepeatables = function(rep) {
		this.repeatables.push(rep);
	};

	PageElementWriter.prototype.popFromRepeatables = function() {
		this.repeatables.pop();
	};

	PageElementWriter.prototype.context = function() {
		return this.writer.context;
	};

	module.exports = PageElementWriter;


/***/ },
/* 22 */
/***/ function(module, exports, __webpack_require__) {

	/* jslint node: true */
	'use strict';

	function buildColumnWidths(columns, availableWidth) {
		var autoColumns = [],
			autoMin = 0, autoMax = 0,
			starColumns = [],
			starMaxMin = 0,
			starMaxMax = 0,
			fixedColumns = [],
			initial_availableWidth = availableWidth;

		columns.forEach(function(column) {
			if (isAutoColumn(column)) {
				autoColumns.push(column);
				autoMin += column._minWidth;
				autoMax += column._maxWidth;
			} else if (isStarColumn(column)) {
				starColumns.push(column);
				starMaxMin = Math.max(starMaxMin, column._minWidth);
				starMaxMax = Math.max(starMaxMax, column._maxWidth);
			} else {
				fixedColumns.push(column);
			}
		});

		fixedColumns.forEach(function(col) {
			// width specified as %
			if (typeof col.width === 'string' && /\d+%/.test(col.width) ) {
				col.width = parseFloat(col.width)*initial_availableWidth/100;
			}
			if (col.width < (col._minWidth) && col.elasticWidth) {
				col._calcWidth = col._minWidth;
			} else {
				col._calcWidth = col.width;
			}

			availableWidth -= col._calcWidth;
		});

		// http://www.freesoft.org/CIE/RFC/1942/18.htm
		// http://www.w3.org/TR/CSS2/tables.html#width-layout
		// http://dev.w3.org/csswg/css3-tables-algorithms/Overview.src.htm
		var minW = autoMin + starMaxMin * starColumns.length;
		var maxW = autoMax + starMaxMax * starColumns.length;
		if (minW >= availableWidth) {
			// case 1 - there's no way to fit all columns within available width
			// that's actually pretty bad situation with PDF as we have no horizontal scroll
			// no easy workaround (unless we decide, in the future, to split single words)
			// currently we simply use minWidths for all columns
			autoColumns.forEach(function(col) {
				col._calcWidth = col._minWidth;
			});

			starColumns.forEach(function(col) {
				col._calcWidth = starMaxMin; // starMaxMin already contains padding
			});
		} else {
			if (maxW < availableWidth) {
				// case 2 - we can fit rest of the table within available space
				autoColumns.forEach(function(col) {
					col._calcWidth = col._maxWidth;
					availableWidth -= col._calcWidth;
				});
			} else {
				// maxW is too large, but minW fits within available width
				var W = availableWidth - minW;
				var D = maxW - minW;

				autoColumns.forEach(function(col) {
					var d = col._maxWidth - col._minWidth;
					col._calcWidth = col._minWidth + d * W / D;
					availableWidth -= col._calcWidth;
				});
			}

			if (starColumns.length > 0) {
				var starSize = availableWidth / starColumns.length;

				starColumns.forEach(function(col) {
					col._calcWidth = starSize;
				});
			}
		}
	}

	function isAutoColumn(column) {
		return column.width === 'auto';
	}

	function isStarColumn(column) {
		return column.width === null || column.width === undefined || column.width === '*' || column.width === 'star';
	}

	//TODO: refactor and reuse in measureTable
	function measureMinMax(columns) {
		var result = { min: 0, max: 0 };

		var maxStar = { min: 0, max: 0 };
		var starCount = 0;

		for(var i = 0, l = columns.length; i < l; i++) {
			var c = columns[i];

			if (isStarColumn(c)) {
				maxStar.min = Math.max(maxStar.min, c._minWidth);
				maxStar.max = Math.max(maxStar.max, c._maxWidth);
				starCount++;
			} else if (isAutoColumn(c)) {
				result.min += c._minWidth;
				result.max += c._maxWidth;
			} else {
				result.min += ((c.width !== undefined && c.width) || c._minWidth);
				result.max += ((c.width  !== undefined && c.width) || c._maxWidth);
			}
		}

		if (starCount) {
			result.min += starCount * maxStar.min;
			result.max += starCount * maxStar.max;
		}

		return result;
	}

	/**
	* Calculates column widths
	* @private
	*/
	module.exports = {
		buildColumnWidths: buildColumnWidths,
		measureMinMax: measureMinMax,
		isAutoColumn: isAutoColumn,
		isStarColumn: isStarColumn
	};


/***/ },
/* 23 */
/***/ function(module, exports, __webpack_require__) {

	/* jslint node: true */
	'use strict';

	var ColumnCalculator = __webpack_require__(22);

	function TableProcessor(tableNode) {
	  this.tableNode = tableNode;
	}

	TableProcessor.prototype.beginTable = function(writer) {
	  var tableNode;
	  var availableWidth;
	  var self = this;

	  tableNode = this.tableNode;
	  this.offsets = tableNode._offsets;
	  this.layout = tableNode._layout;

	  availableWidth = writer.context().availableWidth - this.offsets.total;
	  ColumnCalculator.buildColumnWidths(tableNode.table.widths, availableWidth);

	  this.tableWidth = tableNode._offsets.total + getTableInnerContentWidth();
	  this.rowSpanData = prepareRowSpanData();
	  this.cleanUpRepeatables = false;

	  this.headerRows = tableNode.table.headerRows || 0;
	  this.rowsWithoutPageBreak = this.headerRows + (tableNode.table.keepWithHeaderRows || 0);
	  this.dontBreakRows = tableNode.table.dontBreakRows || false;

	  if (this.rowsWithoutPageBreak) {
	    writer.beginUnbreakableBlock();
	  }

	  this.drawHorizontalLine(0, writer);

	  function getTableInnerContentWidth() {
	    var width = 0;

	    tableNode.table.widths.forEach(function(w) {
	      width += w._calcWidth;
	    });

	    return width;
	  }

	  function prepareRowSpanData() {
	    var rsd = [];
	    var x = 0;
	    var lastWidth = 0;

	    rsd.push({ left: 0, rowSpan: 0 });

	    for(var i = 0, l = self.tableNode.table.body[0].length; i < l; i++) {
	      var paddings = self.layout.paddingLeft(i, self.tableNode) + self.layout.paddingRight(i, self.tableNode);
	      var lBorder = self.layout.vLineWidth(i, self.tableNode);
	      lastWidth = paddings + lBorder + self.tableNode.table.widths[i]._calcWidth;
	      rsd[rsd.length - 1].width = lastWidth;
	      x += lastWidth;
	      rsd.push({ left: x, rowSpan: 0, width: 0 });
	    }

	    return rsd;
	  }
	};

	TableProcessor.prototype.onRowBreak = function(rowIndex, writer) {
	  var self = this;
	  return function() {
	    //console.log('moving by : ', topLineWidth, rowPaddingTop);
	    var offset = self.rowPaddingTop + (!self.headerRows ? self.topLineWidth : 0);
	    writer.context().moveDown(offset);
	  };

	};

	TableProcessor.prototype.beginRow = function(rowIndex, writer) {
	  this.topLineWidth = this.layout.hLineWidth(rowIndex, this.tableNode);
	  this.rowPaddingTop = this.layout.paddingTop(rowIndex, this.tableNode);
	  this.bottomLineWidth = this.layout.hLineWidth(rowIndex+1, this.tableNode);
	  this.rowPaddingBottom = this.layout.paddingBottom(rowIndex, this.tableNode);

	  this.rowCallback = this.onRowBreak(rowIndex, writer);
	  writer.tracker.startTracking('pageChanged', this.rowCallback );
	    if(this.dontBreakRows) {
	        writer.beginUnbreakableBlock();
	    }
	  this.rowTopY = writer.context().y;
	  this.reservedAtBottom = this.bottomLineWidth + this.rowPaddingBottom;

	  writer.context().availableHeight -= this.reservedAtBottom;

	  writer.context().moveDown(this.rowPaddingTop);
	};

	TableProcessor.prototype.drawHorizontalLine = function(lineIndex, writer, overrideY) {
	  var lineWidth = this.layout.hLineWidth(lineIndex, this.tableNode);
	  if (lineWidth) {
	    var offset = lineWidth / 2;
	    var currentLine = null;

	    for(var i = 0, l = this.rowSpanData.length; i < l; i++) {
	      var data = this.rowSpanData[i];
	      var shouldDrawLine = !data.rowSpan;

	      if (!currentLine && shouldDrawLine) {
	        currentLine = { left: data.left, width: 0 };
	      }

	      if (shouldDrawLine) {
	        currentLine.width += (data.width || 0);
	      }

	      var y = (overrideY || 0) + offset;

	      if (!shouldDrawLine || i === l - 1) {
	        if (currentLine) {
	          writer.addVector({
	            type: 'line',
	            x1: currentLine.left,
	            x2: currentLine.left + currentLine.width,
	            y1: y,
	            y2: y,
	            lineWidth: lineWidth,
	            lineColor: typeof this.layout.hLineColor === 'function' ? this.layout.hLineColor(lineIndex, this.tableNode) : this.layout.hLineColor
	          }, false, overrideY);
	          currentLine = null;
	        }
	      }
	    }

	    writer.context().moveDown(lineWidth);
	  }
	};

	TableProcessor.prototype.drawVerticalLine = function(x, y0, y1, vLineIndex, writer) {
	  var width = this.layout.vLineWidth(vLineIndex, this.tableNode);
	  if (width === 0) return;
	  writer.addVector({
	    type: 'line',
	    x1: x + width/2,
	    x2: x + width/2,
	    y1: y0,
	    y2: y1,
	    lineWidth: width,
	    lineColor: typeof this.layout.vLineColor === 'function' ? this.layout.vLineColor(vLineIndex, this.tableNode) : this.layout.vLineColor
	  }, false, true);
	};

	TableProcessor.prototype.endTable = function(writer) {
	  if (this.cleanUpRepeatables) {
	    writer.popFromRepeatables();
	  }
	};

	TableProcessor.prototype.endRow = function(rowIndex, writer, pageBreaks) {
	    var l, i;
	    var self = this;
	    writer.tracker.stopTracking('pageChanged', this.rowCallback);
	    writer.context().moveDown(this.layout.paddingBottom(rowIndex, this.tableNode));
	    writer.context().availableHeight += this.reservedAtBottom;

	    var endingPage = writer.context().page;
	    var endingY = writer.context().y;

	    var xs = getLineXs();

	    var ys = [];

	    var hasBreaks = pageBreaks && pageBreaks.length > 0;

	    ys.push({
	      y0: this.rowTopY,
	      page: hasBreaks ? pageBreaks[0].prevPage : endingPage
	    });

	    if (hasBreaks) {
	      for(i = 0, l = pageBreaks.length; i < l; i++) {
	        var pageBreak = pageBreaks[i];
	        ys[ys.length - 1].y1 = pageBreak.prevY;

	        ys.push({y0: pageBreak.y, page: pageBreak.prevPage + 1});
	      }
	    }

	    ys[ys.length - 1].y1 = endingY;

	    var skipOrphanePadding = (ys[0].y1 - ys[0].y0 === this.rowPaddingTop);
	    for(var yi = (skipOrphanePadding ? 1 : 0), yl = ys.length; yi < yl; yi++) {
	      var willBreak = yi < ys.length - 1;
	      var rowBreakWithoutHeader = (yi > 0 && !this.headerRows);
	      var hzLineOffset =  rowBreakWithoutHeader ? 0 : this.topLineWidth;
	      var y1 = ys[yi].y0;
	      var y2 = ys[yi].y1;

				if(willBreak) {
					y2 = y2 + this.rowPaddingBottom;
				}

	      if (writer.context().page != ys[yi].page) {
	        writer.context().page = ys[yi].page;

	        //TODO: buggy, availableHeight should be updated on every pageChanged event
	        // TableProcessor should be pageChanged listener, instead of processRow
	        this.reservedAtBottom = 0;
	      }

	      for(i = 0, l = xs.length; i < l; i++) {
	        this.drawVerticalLine(xs[i].x, y1 - hzLineOffset, y2 + this.bottomLineWidth, xs[i].index, writer);
	        if(i < l-1) {
	          var colIndex = xs[i].index;
	          var fillColor=  this.tableNode.table.body[rowIndex][colIndex].fillColor;
	          if(fillColor ) {
	            var wBorder = this.layout.vLineWidth(colIndex, this.tableNode);
	            var xf = xs[i].x+wBorder;
	            var yf = y1 - hzLineOffset;
	            writer.addVector({
	              type: 'rect',
	              x: xf,
	              y: yf,
	              w: xs[i+1].x-xf,
	              h: y2+this.bottomLineWidth-yf,
	              lineWidth: 0,
	              color: fillColor
	            }, false, true, 0);
	          }
	        }
	      }

	      if (willBreak && this.layout.hLineWhenBroken !== false) {
	        this.drawHorizontalLine(rowIndex + 1, writer, y2);
	      }
	      if(rowBreakWithoutHeader && this.layout.hLineWhenBroken !== false) {
	        this.drawHorizontalLine(rowIndex, writer, y1);
	      }
	    }

	    writer.context().page = endingPage;
	    writer.context().y = endingY;

	    var row = this.tableNode.table.body[rowIndex];
	    for(i = 0, l = row.length; i < l; i++) {
	      if (row[i].rowSpan) {
	        this.rowSpanData[i].rowSpan = row[i].rowSpan;

	        // fix colSpans
	        if (row[i].colSpan && row[i].colSpan > 1) {
	          for(var j = 1; j < row[i].rowSpan; j++) {
	            this.tableNode.table.body[rowIndex + j][i]._colSpan = row[i].colSpan;
	          }
	        }
	      }

	      if(this.rowSpanData[i].rowSpan > 0) {
	        this.rowSpanData[i].rowSpan--;
	      }
	    }

	    this.drawHorizontalLine(rowIndex + 1, writer);

	    if(this.headerRows && rowIndex === this.headerRows - 1) {
	      this.headerRepeatable = writer.currentBlockToRepeatable();
	    }

	    if(this.dontBreakRows) {
	      writer.tracker.auto('pageChanged',
	        function() {
	          self.drawHorizontalLine(rowIndex, writer);
	        },
	        function() {
	          writer.commitUnbreakableBlock();
	          self.drawHorizontalLine(rowIndex, writer);
	        }
	      );
	    }

	    if(this.headerRepeatable && (rowIndex === (this.rowsWithoutPageBreak - 1) || rowIndex === this.tableNode.table.body.length - 1)) {
	      writer.commitUnbreakableBlock();
	      writer.pushToRepeatables(this.headerRepeatable);
	      this.cleanUpRepeatables = true;
	      this.headerRepeatable = null;
	    }

	    function getLineXs() {
	      var result = [];
	      var cols = 0;

	      for(var i = 0, l = self.tableNode.table.body[rowIndex].length; i < l; i++) {
	        if (!cols) {
	          result.push({ x: self.rowSpanData[i].left, index: i});

	          var item = self.tableNode.table.body[rowIndex][i];
	          cols = (item._colSpan || item.colSpan || 0);
	        }
	        if (cols > 0) {
	          cols--;
	        }
	      }

	      result.push({ x: self.rowSpanData[self.rowSpanData.length - 1].left, index: self.rowSpanData.length - 1});

	      return result;
	    }
	};

	module.exports = TableProcessor;


/***/ },
/* 24 */
/***/ function(module, exports, __webpack_require__) {

	/* jslint node: true */
	'use strict';

	/**
	* Creates an instance of Line
	*
	* @constructor
	* @this {Line}
	* @param {Number} Maximum width this line can have
	*/
	function Line(maxWidth) {
		this.maxWidth = maxWidth;
		this.leadingCut = 0;
		this.trailingCut = 0;
		this.inlineWidths = 0;
		this.inlines = [];
	}

	Line.prototype.getAscenderHeight = function() {
		var y = 0;

		this.inlines.forEach(function(inline) {
			y = Math.max(y, inline.font.ascender / 1000 * inline.fontSize);
		});
		return y;
	};

	Line.prototype.hasEnoughSpaceForInline = function(inline) {
		if (this.inlines.length === 0) return true;
		if (this.newLineForced) return false;

		return this.inlineWidths + inline.width - this.leadingCut - (inline.trailingCut || 0) <= this.maxWidth;
	};

	Line.prototype.addInline = function(inline) {
		if (this.inlines.length === 0) {
			this.leadingCut = inline.leadingCut || 0;
		}
		this.trailingCut = inline.trailingCut || 0;

		inline.x = this.inlineWidths - this.leadingCut;

		this.inlines.push(inline);
		this.inlineWidths += inline.width;

		if (inline.lineEnd) {
			this.newLineForced = true;
		}
	};

	Line.prototype.getWidth = function() {
		return this.inlineWidths - this.leadingCut - this.trailingCut;
	};

	/**
	* Returns line height
	* @return {Number}
	*/
	Line.prototype.getHeight = function() {
		var max = 0;

		this.inlines.forEach(function(item) {
			max = Math.max(max, item.height || 0);
		});

		return max;
	};

	module.exports = Line;


/***/ },
/* 25 */
/***/ function(module, exports, __webpack_require__) {

	/* jslint node: true */
	'use strict';

	function pack() {
		var result = {};

		for(var i = 0, l = arguments.length; i < l; i++) {
			var obj = arguments[i];

			if (obj) {
				for(var key in obj) {
					if (obj.hasOwnProperty(key)) {
						result[key] = obj[key];
					}
				}
			}
		}

		return result;
	}

	function offsetVector(vector, x, y) {
		switch(vector.type) {
		case 'ellipse':
		case 'rect':
			vector.x += x;
			vector.y += y;
			break;
		case 'line':
			vector.x1 += x;
			vector.x2 += x;
			vector.y1 += y;
			vector.y2 += y;
			break;
		case 'polyline':
			for(var i = 0, l = vector.points.length; i < l; i++) {
				vector.points[i].x += x;
				vector.points[i].y += y;
			}
			break;
		}
	}

	function fontStringify(key, val) {
		if (key === 'font') {
			return 'font';
		}
		return val;
	}

	function isFunction(functionToCheck) {
		var getType = {};
		return functionToCheck && getType.toString.call(functionToCheck) === '[object Function]';
	}


	module.exports = {
		pack: pack,
		fontStringify: fontStringify,
		offsetVector: offsetVector,
		isFunction: isFunction
	};


/***/ },
/* 26 */
/***/ function(module, exports, __webpack_require__) {

	/* jslint node: true */
	'use strict';

	var WORD_RE = /([^ ,\/!.?:;\-\n]*[ ,\/!.?:;\-]*)|\n/g;
	// /\S*\s*/g to be considered (I'm not sure however - we shouldn't split 'aaa !!!!')

	var LEADING = /^(\s)+/g;
	var TRAILING = /(\s)+$/g;

	/**
	* Creates an instance of TextTools - text measurement utility
	*
	* @constructor
	* @param {FontProvider} fontProvider
	*/
	function TextTools(fontProvider) {
		this.fontProvider = fontProvider;
	}

	/**
	* Converts an array of strings (or inline-definition-objects) into a set of inlines
	* and their min/max widths
	* @param  {Object} textArray - an array of inline-definition-objects (or strings)
	* @param  {Number} maxWidth - max width a single Line should have
	* @return {Array} an array of Lines
	*/
	TextTools.prototype.buildInlines = function(textArray, styleContextStack) {
		var measured = measure(this.fontProvider, textArray, styleContextStack);

		var minWidth = 0,
			maxWidth = 0,
			currentLineWidth;

		measured.forEach(function (inline) {
			minWidth = Math.max(minWidth, inline.width - inline.leadingCut - inline.trailingCut);

			if (!currentLineWidth) {
				currentLineWidth = { width: 0, leadingCut: inline.leadingCut, trailingCut: 0 };
			}

			currentLineWidth.width += inline.width;
			currentLineWidth.trailingCut = inline.trailingCut;

			maxWidth = Math.max(maxWidth, getTrimmedWidth(currentLineWidth));

			if (inline.lineEnd) {
				currentLineWidth = null;
			}
		});

		return {
			items: measured,
			minWidth: minWidth,
			maxWidth: maxWidth
		};

		function getTrimmedWidth(item) {
			return Math.max(0, item.width - item.leadingCut - item.trailingCut);
		}
	};

	/**
	* Returns size of the specified string (without breaking it) using the current style
	* @param  {String} text              text to be measured
	* @param  {Object} styleContextStack current style stack
	* @return {Object}                   size of the specified string
	*/
	TextTools.prototype.sizeOfString = function(text, styleContextStack) {
		text = text.replace('\t', '    ');

		//TODO: refactor - extract from measure
		var fontName = getStyleProperty({}, styleContextStack, 'font', 'Roboto');
		var fontSize = getStyleProperty({}, styleContextStack, 'fontSize', 12);
		var bold = getStyleProperty({}, styleContextStack, 'bold', false);
		var italics = getStyleProperty({}, styleContextStack, 'italics', false);
		var lineHeight = getStyleProperty({}, styleContextStack, 'lineHeight', 1);

		var font = this.fontProvider.provideFont(fontName, bold, italics);

		return {
			width: font.widthOfString(removeDiacritics(text), fontSize),
			height: font.lineHeight(fontSize) * lineHeight,
			fontSize: fontSize,
			lineHeight: lineHeight,
			ascender: font.ascender / 1000 * fontSize,
			decender: font.decender / 1000 * fontSize
		};
	};

	function splitWords(text) {
		var results = [];
		text = text.replace('\t', '    ');

		var array = text.match(WORD_RE);

		// i < l - 1, because the last match is always an empty string
		// other empty strings however are treated as new-lines
		for(var i = 0, l = array.length; i < l - 1; i++) {
			var item = array[i];

			var isNewLine = item.length === 0;

			if (!isNewLine) {
				results.push({text: item});
			}
			else {
				var shouldAddLine = (results.length === 0 || results[results.length - 1].lineEnd);

				if (shouldAddLine) {
					results.push({ text: '', lineEnd: true });
				}
				else {
					results[results.length - 1].lineEnd = true;
				}
			}
		}

		return results;
	}

	function copyStyle(source, destination) {
		destination = destination || {};
		source = source || {}; //TODO: default style

		for(var key in source) {
			if (key != 'text' && source.hasOwnProperty(key)) {
				destination[key] = source[key];
			}
		}

		return destination;
	}

	function normalizeTextArray(array) {
		var results = [];

		if (typeof array == 'string' || array instanceof String) {
			array = [ array ];
		}

		for(var i = 0, l = array.length; i < l; i++) {
			var item = array[i];
			var style = null;
			var words;

			if (typeof item == 'string' || item instanceof String) {
				words = splitWords(item);
			} else {
				words = splitWords(item.text);
				style = copyStyle(item);
			}

			for(var i2 = 0, l2 = words.length; i2 < l2; i2++) {
				var result = {
					text: words[i2].text
				};

				if (words[i2].lineEnd) {
					result.lineEnd = true;
				}

				copyStyle(style, result);

				results.push(result);
			}
		}

		return results;
	}

	//TODO: support for other languages (currently only polish is supported)
	var diacriticsMap = { 'Ą': 'A', 'Ć': 'C', 'Ę': 'E', 'Ł': 'L', 'Ń': 'N', 'Ó': 'O', 'Ś': 'S', 'Ź': 'Z', 'Ż': 'Z', 'ą': 'a', 'ć': 'c', 'ę': 'e', 'ł': 'l', 'ń': 'n', 'ó': 'o', 'ś': 's', 'ź': 'z', 'ż': 'z' };
	// '  << atom.io workaround

	function removeDiacritics(text) {
		return text.replace(/[^A-Za-z0-9\[\] ]/g, function(a) {
			return diacriticsMap[a] || a;
		});
	}

	function getStyleProperty(item, styleContextStack, property, defaultValue) {
		var value;

		if (item[property] !== undefined && item[property] !== null) {
			// item defines this property
			return item[property];
		}

		if (!styleContextStack) return defaultValue;

		styleContextStack.auto(item, function() {
			value = styleContextStack.getProperty(property);
		});

		if (value !== null && value !== undefined) {
			return value;
		} else {
			return defaultValue;
		}
	}

	function measure(fontProvider, textArray, styleContextStack) {
		var normalized = normalizeTextArray(textArray);

		normalized.forEach(function(item) {
			var fontName = getStyleProperty(item, styleContextStack, 'font', 'Roboto');
			var fontSize = getStyleProperty(item, styleContextStack, 'fontSize', 12);
			var bold = getStyleProperty(item, styleContextStack, 'bold', false);
			var italics = getStyleProperty(item, styleContextStack, 'italics', false);
			var color = getStyleProperty(item, styleContextStack, 'color', 'black');
			var decoration = getStyleProperty(item, styleContextStack, 'decoration', null);
			var decorationColor = getStyleProperty(item, styleContextStack, 'decorationColor', null);
			var decorationStyle = getStyleProperty(item, styleContextStack, 'decorationStyle', null);
			var background = getStyleProperty(item, styleContextStack, 'background', null);
			var lineHeight = getStyleProperty(item, styleContextStack, 'lineHeight', 1);

			var font = fontProvider.provideFont(fontName, bold, italics);

			// TODO: character spacing
			item.width = font.widthOfString(removeDiacritics(item.text), fontSize);
			item.height = font.lineHeight(fontSize) * lineHeight;

			var leadingSpaces = item.text.match(LEADING);
			var trailingSpaces = item.text.match(TRAILING);
			if (leadingSpaces) {
				item.leadingCut = font.widthOfString(leadingSpaces[0], fontSize);
			}
			else {
				item.leadingCut = 0;
			}

			if (trailingSpaces) {
				item.trailingCut = font.widthOfString(trailingSpaces[0], fontSize);
			}
			else {
				item.trailingCut = 0;
			}

			item.alignment = getStyleProperty(item, styleContextStack, 'alignment', 'left');
			item.font = font;
			item.fontSize = fontSize;
			item.color = color;
			item.decoration = decoration;
			item.decorationColor = decorationColor;
			item.decorationStyle = decorationStyle;
			item.background = background;
		});

		return normalized;
	}

	/****TESTS**** (add a leading '/' to uncomment)
	TextTools.prototype.splitWords = splitWords;
	TextTools.prototype.normalizeTextArray = normalizeTextArray;
	TextTools.prototype.measure = measure;
	// */


	module.exports = TextTools;


/***/ },
/* 27 */
/***/ function(module, exports, __webpack_require__) {

	/* jslint node: true */
	'use strict';

	/**
	* Creates an instance of StyleContextStack used for style inheritance and style overrides
	*
	* @constructor
	* @this {StyleContextStack}
	* @param {Object} named styles dictionary
	* @param {Object} optional default style definition
	*/
	function StyleContextStack (styleDictionary, defaultStyle) {
		this.defaultStyle = defaultStyle || {};
		this.styleDictionary = styleDictionary;
		this.styleOverrides = [];
	}

	/**
	* Creates cloned version of current stack
	* @return {StyleContextStack} current stack snapshot
	*/
	StyleContextStack.prototype.clone = function() {
		var stack = new StyleContextStack(this.styleDictionary, this.defaultStyle);

		this.styleOverrides.forEach(function(item) {
			stack.styleOverrides.push(item);
		});

		return stack;
	};

	/**
	* Pushes style-name or style-overrides-object onto the stack for future evaluation
	*
	* @param {String|Object} styleNameOrOverride style-name (referring to styleDictionary) or
	*                                            a new dictionary defining overriding properties
	*/
	StyleContextStack.prototype.push = function(styleNameOrOverride) {
		this.styleOverrides.push(styleNameOrOverride);
	};

	/**
	* Removes last style-name or style-overrides-object from the stack
	*
	* @param {Number} howMany - optional number of elements to be popped (if not specified,
	*                           one element will be removed from the stack)
	*/
	StyleContextStack.prototype.pop = function(howMany) {
		howMany = howMany || 1;

		while(howMany-- > 0) {
			this.styleOverrides.pop();
		}
	};

	/**
	* Creates a set of named styles or/and a style-overrides-object based on the item,
	* pushes those elements onto the stack for future evaluation and returns the number
	* of elements pushed, so they can be easily poped then.
	*
	* @param {Object} item - an object with optional style property and/or style overrides
	* @return the number of items pushed onto the stack
	*/
	StyleContextStack.prototype.autopush = function(item) {
		if (typeof item === 'string' || item instanceof String) return 0;

		var styleNames = [];

		if (item.style) {
			if (item.style instanceof Array) {
				styleNames = item.style;
			} else {
				styleNames = [ item.style ];
			}
		}

		for(var i = 0, l = styleNames.length; i < l; i++) {
			this.push(styleNames[i]);
		}

		var styleOverrideObject = {};
		var pushSOO = false;

		[
			'font',
			'fontSize',
			'bold',
			'italics',
			'alignment',
			'color',
			'columnGap',
			'fillColor',
			'decoration',
			'decorationStyle',
			'decorationColor',
			'background',
			'lineHeight'
			//'tableCellPadding'
			// 'cellBorder',
			// 'headerCellBorder',
			// 'oddRowCellBorder',
			// 'evenRowCellBorder',
			// 'tableBorder'
		].forEach(function(key) {
			if (item[key] !== undefined && item[key] !== null) {
				styleOverrideObject[key] = item[key];
				pushSOO = true;
			}
		});

		if (pushSOO) {
			this.push(styleOverrideObject);
		}

		return styleNames.length + (pushSOO ? 1 : 0);
	};

	/**
	* Automatically pushes elements onto the stack, using autopush based on item,
	* executes callback and then pops elements back. Returns value returned by callback
	*
	* @param  {Object}   item - an object with optional style property and/or style overrides
	* @param  {Function} function to be called between autopush and pop
	* @return {Object} value returned by callback
	*/
	StyleContextStack.prototype.auto = function(item, callback) {
		var pushedItems = this.autopush(item);
		var result = callback();

		if (pushedItems > 0) {
			this.pop(pushedItems);
		}

		return result;
	};

	/**
	* Evaluates stack and returns value of a named property
	*
	* @param {String} property - property name
	* @return property value or null if not found
	*/
	StyleContextStack.prototype.getProperty = function(property) {
		if (this.styleOverrides) {
			for(var i = this.styleOverrides.length - 1; i >= 0; i--) {
				var item = this.styleOverrides[i];

				if (typeof item == 'string' || item instanceof String) {
					// named-style-override

					var style = this.styleDictionary[item];
					if (style && style[property] !== null && style[property] !== undefined) {
						return style[property];
					}
				} else {
					// style-overrides-object
					if (item[property] !== undefined && item[property] !== null) {
						return item[property];
					}
				}
			}
		}

		return this.defaultStyle && this.defaultStyle[property];
	};

	module.exports = StyleContextStack;


/***/ },
/* 28 */
/***/ function(module, exports, __webpack_require__) {

	/* WEBPACK VAR INJECTION */(function(Buffer) {// Generated by CoffeeScript 1.7.1

	/*
	PDFDocument - represents an entire PDF document
	By Devon Govett
	 */

	(function() {
	  var PDFDocument, PDFObject, PDFPage, PDFReference, fs, stream,
	    __hasProp = {}.hasOwnProperty,
	    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

	  stream = __webpack_require__(46);

	  fs = __webpack_require__(10);

	  PDFObject = __webpack_require__(32);

	  PDFReference = __webpack_require__(12);

	  PDFPage = __webpack_require__(38);

	  PDFDocument = (function(_super) {
	    var mixin;

	    __extends(PDFDocument, _super);

	    function PDFDocument(options) {
	      var key, val, _ref, _ref1;
	      this.options = options != null ? options : {};
	      PDFDocument.__super__.constructor.apply(this, arguments);
	      this.version = 1.3;
	      this.compress = (_ref = this.options.compress) != null ? _ref : true;
	      this._pageBuffer = [];
	      this._pageBufferStart = 0;
	      this._offsets = [];
	      this._waiting = 0;
	      this._ended = false;
	      this._offset = 0;
	      this._root = this.ref({
	        Type: 'Catalog',
	        Pages: this.ref({
	          Type: 'Pages',
	          Count: 0,
	          Kids: []
	        })
	      });
	      this.page = null;
	      this.initColor();
	      this.initVector();
	      this.initFonts();
	      this.initText();
	      this.initImages();
	      this.info = {
	        Producer: 'PDFKit',
	        Creator: 'PDFKit',
	        CreationDate: new Date()
	      };
	      if (this.options.info) {
	        _ref1 = this.options.info;
	        for (key in _ref1) {
	          val = _ref1[key];
	          this.info[key] = val;
	        }
	      }
	      this._write("%PDF-" + this.version);
	      this._write("%\xFF\xFF\xFF\xFF");
	      this.addPage();
	    }

	    mixin = function(methods) {
	      var method, name, _results;
	      _results = [];
	      for (name in methods) {
	        method = methods[name];
	        _results.push(PDFDocument.prototype[name] = method);
	      }
	      return _results;
	    };

	    mixin(__webpack_require__(41));

	    mixin(__webpack_require__(39));

	    mixin(__webpack_require__(44));

	    mixin(__webpack_require__(40));

	    mixin(__webpack_require__(42));

	    mixin(__webpack_require__(43));

	    PDFDocument.prototype.addPage = function(options) {
	      var pages;
	      if (options == null) {
	        options = this.options;
	      }
	      if (!this.options.bufferPages) {
	        this.flushPages();
	      }
	      this.page = new PDFPage(this, options);
	      this._pageBuffer.push(this.page);
	      pages = this._root.data.Pages.data;
	      pages.Kids.push(this.page.dictionary);
	      pages.Count++;
	      this.x = this.page.margins.left;
	      this.y = this.page.margins.top;
	      this._ctm = [1, 0, 0, 1, 0, 0];
	      this.transform(1, 0, 0, -1, 0, this.page.height);
	      return this;
	    };

	    PDFDocument.prototype.bufferedPageRange = function() {
	      return {
	        start: this._pageBufferStart,
	        count: this._pageBuffer.length
	      };
	    };

	    PDFDocument.prototype.switchToPage = function(n) {
	      var page;
	      if (!(page = this._pageBuffer[n - this._pageBufferStart])) {
	        throw new Error("switchToPage(" + n + ") out of bounds, current buffer covers pages " + this._pageBufferStart + " to " + (this._pageBufferStart + this._pageBuffer.length - 1));
	      }
	      return this.page = page;
	    };

	    PDFDocument.prototype.flushPages = function() {
	      var page, pages, _i, _len;
	      pages = this._pageBuffer;
	      this._pageBuffer = [];
	      this._pageBufferStart += pages.length;
	      for (_i = 0, _len = pages.length; _i < _len; _i++) {
	        page = pages[_i];
	        page.end();
	      }
	    };

	    PDFDocument.prototype.ref = function(data) {
	      var ref;
	      ref = new PDFReference(this, this._offsets.length + 1, data);
	      this._offsets.push(null);
	      this._waiting++;
	      return ref;
	    };

	    PDFDocument.prototype._read = function() {};

	    PDFDocument.prototype._write = function(data) {
	      if (!Buffer.isBuffer(data)) {
	        data = new Buffer(data + '\n', 'binary');
	      }
	      this.push(data);
	      return this._offset += data.length;
	    };

	    PDFDocument.prototype.addContent = function(data) {
	      this.page.write(data);
	      return this;
	    };

	    PDFDocument.prototype._refEnd = function(ref) {
	      this._offsets[ref.id - 1] = ref.offset;
	      if (--this._waiting === 0 && this._ended) {
	        this._finalize();
	        return this._ended = false;
	      }
	    };

	    PDFDocument.prototype.write = function(filename, fn) {
	      var err;
	      err = new Error('PDFDocument#write is deprecated, and will be removed in a future version of PDFKit. Please pipe the document into a Node stream.');
	      console.warn(err.stack);
	      this.pipe(fs.createWriteStream(filename));
	      this.end();
	      return this.once('end', fn);
	    };

	    PDFDocument.prototype.output = function(fn) {
	      throw new Error('PDFDocument#output is deprecated, and has been removed from PDFKit. Please pipe the document into a Node stream.');
	    };

	    PDFDocument.prototype.end = function() {
	      var font, key, name, val, _ref, _ref1;
	      this.flushPages();
	      this._info = this.ref();
	      _ref = this.info;
	      for (key in _ref) {
	        val = _ref[key];
	        if (typeof val === 'string') {
	          val = new String(val);
	        }
	        this._info.data[key] = val;
	      }
	      this._info.end();
	      _ref1 = this._fontFamilies;
	      for (name in _ref1) {
	        font = _ref1[name];
	        font.embed();
	      }
	      this._root.end();
	      this._root.data.Pages.end();
	      if (this._waiting === 0) {
	        return this._finalize();
	      } else {
	        return this._ended = true;
	      }
	    };

	    PDFDocument.prototype._finalize = function(fn) {
	      var offset, xRefOffset, _i, _len, _ref;
	      xRefOffset = this._offset;
	      this._write("xref");
	      this._write("0 " + (this._offsets.length + 1));
	      this._write("0000000000 65535 f ");
	      _ref = this._offsets;
	      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
	        offset = _ref[_i];
	        offset = ('0000000000' + offset).slice(-10);
	        this._write(offset + ' 00000 n ');
	      }
	      this._write('trailer');
	      this._write(PDFObject.convert({
	        Size: this._offsets.length + 1,
	        Root: this._root,
	        Info: this._info
	      }));
	      this._write('startxref');
	      this._write("" + xRefOffset);
	      this._write('%%EOF');
	      return this.push(null);
	    };

	    PDFDocument.prototype.toString = function() {
	      return "[object PDFDocument]";
	    };

	    return PDFDocument;

	  })(stream.Readable);

	  module.exports = PDFDocument;

	}).call(this);

	/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(4).Buffer))

/***/ },
/* 29 */
/***/ function(module, exports, __webpack_require__) {

	exports.read = function(buffer, offset, isLE, mLen, nBytes) {
	  var e, m,
	      eLen = nBytes * 8 - mLen - 1,
	      eMax = (1 << eLen) - 1,
	      eBias = eMax >> 1,
	      nBits = -7,
	      i = isLE ? (nBytes - 1) : 0,
	      d = isLE ? -1 : 1,
	      s = buffer[offset + i];

	  i += d;

	  e = s & ((1 << (-nBits)) - 1);
	  s >>= (-nBits);
	  nBits += eLen;
	  for (; nBits > 0; e = e * 256 + buffer[offset + i], i += d, nBits -= 8);

	  m = e & ((1 << (-nBits)) - 1);
	  e >>= (-nBits);
	  nBits += mLen;
	  for (; nBits > 0; m = m * 256 + buffer[offset + i], i += d, nBits -= 8);

	  if (e === 0) {
	    e = 1 - eBias;
	  } else if (e === eMax) {
	    return m ? NaN : ((s ? -1 : 1) * Infinity);
	  } else {
	    m = m + Math.pow(2, mLen);
	    e = e - eBias;
	  }
	  return (s ? -1 : 1) * m * Math.pow(2, e - mLen);
	};

	exports.write = function(buffer, value, offset, isLE, mLen, nBytes) {
	  var e, m, c,
	      eLen = nBytes * 8 - mLen - 1,
	      eMax = (1 << eLen) - 1,
	      eBias = eMax >> 1,
	      rt = (mLen === 23 ? Math.pow(2, -24) - Math.pow(2, -77) : 0),
	      i = isLE ? 0 : (nBytes - 1),
	      d = isLE ? 1 : -1,
	      s = value < 0 || (value === 0 && 1 / value < 0) ? 1 : 0;

	  value = Math.abs(value);

	  if (isNaN(value) || value === Infinity) {
	    m = isNaN(value) ? 1 : 0;
	    e = eMax;
	  } else {
	    e = Math.floor(Math.log(value) / Math.LN2);
	    if (value * (c = Math.pow(2, -e)) < 1) {
	      e--;
	      c *= 2;
	    }
	    if (e + eBias >= 1) {
	      value += rt / c;
	    } else {
	      value += rt * Math.pow(2, 1 - eBias);
	    }
	    if (value * c >= 2) {
	      e++;
	      c /= 2;
	    }

	    if (e + eBias >= eMax) {
	      m = 0;
	      e = eMax;
	    } else if (e + eBias >= 1) {
	      m = (value * c - 1) * Math.pow(2, mLen);
	      e = e + eBias;
	    } else {
	      m = value * Math.pow(2, eBias - 1) * Math.pow(2, mLen);
	      e = 0;
	    }
	  }

	  for (; mLen >= 8; buffer[offset + i] = m & 0xff, i += d, m /= 256, mLen -= 8);

	  e = (e << mLen) | m;
	  eLen += mLen;
	  for (; eLen > 0; buffer[offset + i] = e & 0xff, i += d, e /= 256, eLen -= 8);

	  buffer[offset + i - d] |= s * 128;
	};


/***/ },
/* 30 */
/***/ function(module, exports, __webpack_require__) {

	
	/**
	 * isArray
	 */

	var isArray = Array.isArray;

	/**
	 * toString
	 */

	var str = Object.prototype.toString;

	/**
	 * Whether or not the given `val`
	 * is an array.
	 *
	 * example:
	 *
	 *        isArray([]);
	 *        // > true
	 *        isArray(arguments);
	 *        // > false
	 *        isArray('');
	 *        // > false
	 *
	 * @param {mixed} val
	 * @return {bool}
	 */

	module.exports = isArray || function (val) {
	  return !! val && '[object Array]' == str.call(val);
	};


/***/ },
/* 31 */
/***/ function(module, exports, __webpack_require__) {

	var lookup = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';

	;(function (exports) {
		'use strict';

	  var Arr = (typeof Uint8Array !== 'undefined')
	    ? Uint8Array
	    : Array

		var PLUS   = '+'.charCodeAt(0)
		var SLASH  = '/'.charCodeAt(0)
		var NUMBER = '0'.charCodeAt(0)
		var LOWER  = 'a'.charCodeAt(0)
		var UPPER  = 'A'.charCodeAt(0)
		var PLUS_URL_SAFE = '-'.charCodeAt(0)
		var SLASH_URL_SAFE = '_'.charCodeAt(0)

		function decode (elt) {
			var code = elt.charCodeAt(0)
			if (code === PLUS ||
			    code === PLUS_URL_SAFE)
				return 62 // '+'
			if (code === SLASH ||
			    code === SLASH_URL_SAFE)
				return 63 // '/'
			if (code < NUMBER)
				return -1 //no match
			if (code < NUMBER + 10)
				return code - NUMBER + 26 + 26
			if (code < UPPER + 26)
				return code - UPPER
			if (code < LOWER + 26)
				return code - LOWER + 26
		}

		function b64ToByteArray (b64) {
			var i, j, l, tmp, placeHolders, arr

			if (b64.length % 4 > 0) {
				throw new Error('Invalid string. Length must be a multiple of 4')
			}

			// the number of equal signs (place holders)
			// if there are two placeholders, than the two characters before it
			// represent one byte
			// if there is only one, then the three characters before it represent 2 bytes
			// this is just a cheap hack to not do indexOf twice
			var len = b64.length
			placeHolders = '=' === b64.charAt(len - 2) ? 2 : '=' === b64.charAt(len - 1) ? 1 : 0

			// base64 is 4/3 + up to two characters of the original data
			arr = new Arr(b64.length * 3 / 4 - placeHolders)

			// if there are placeholders, only get up to the last complete 4 chars
			l = placeHolders > 0 ? b64.length - 4 : b64.length

			var L = 0

			function push (v) {
				arr[L++] = v
			}

			for (i = 0, j = 0; i < l; i += 4, j += 3) {
				tmp = (decode(b64.charAt(i)) << 18) | (decode(b64.charAt(i + 1)) << 12) | (decode(b64.charAt(i + 2)) << 6) | decode(b64.charAt(i + 3))
				push((tmp & 0xFF0000) >> 16)
				push((tmp & 0xFF00) >> 8)
				push(tmp & 0xFF)
			}

			if (placeHolders === 2) {
				tmp = (decode(b64.charAt(i)) << 2) | (decode(b64.charAt(i + 1)) >> 4)
				push(tmp & 0xFF)
			} else if (placeHolders === 1) {
				tmp = (decode(b64.charAt(i)) << 10) | (decode(b64.charAt(i + 1)) << 4) | (decode(b64.charAt(i + 2)) >> 2)
				push((tmp >> 8) & 0xFF)
				push(tmp & 0xFF)
			}

			return arr
		}

		function uint8ToBase64 (uint8) {
			var i,
				extraBytes = uint8.length % 3, // if we have 1 byte left, pad 2 bytes
				output = "",
				temp, length

			function encode (num) {
				return lookup.charAt(num)
			}

			function tripletToBase64 (num) {
				return encode(num >> 18 & 0x3F) + encode(num >> 12 & 0x3F) + encode(num >> 6 & 0x3F) + encode(num & 0x3F)
			}

			// go through the array every three bytes, we'll deal with trailing stuff later
			for (i = 0, length = uint8.length - extraBytes; i < length; i += 3) {
				temp = (uint8[i] << 16) + (uint8[i + 1] << 8) + (uint8[i + 2])
				output += tripletToBase64(temp)
			}

			// pad the end with zeros, but make sure to not forget the extra bytes
			switch (extraBytes) {
				case 1:
					temp = uint8[uint8.length - 1]
					output += encode(temp >> 2)
					output += encode((temp << 4) & 0x3F)
					output += '=='
					break
				case 2:
					temp = (uint8[uint8.length - 2] << 8) + (uint8[uint8.length - 1])
					output += encode(temp >> 10)
					output += encode((temp >> 4) & 0x3F)
					output += encode((temp << 2) & 0x3F)
					output += '='
					break
			}

			return output
		}

		exports.toByteArray = b64ToByteArray
		exports.fromByteArray = uint8ToBase64
	}(false ? (this.base64js = {}) : exports))


/***/ },
/* 32 */
/***/ function(module, exports, __webpack_require__) {

	/* WEBPACK VAR INJECTION */(function(Buffer) {// Generated by CoffeeScript 1.7.1

	/*
	PDFObject - converts JavaScript types into their corrisponding PDF types.
	By Devon Govett
	 */

	(function() {
	  var PDFObject, PDFReference;

	  PDFObject = (function() {
	    var escapable, escapableRe, pad, swapBytes;

	    function PDFObject() {}

	    pad = function(str, length) {
	      return (Array(length + 1).join('0') + str).slice(-length);
	    };

	    escapableRe = /[\n\r\t\b\f\(\)\\]/g;

	    escapable = {
	      '\n': '\\n',
	      '\r': '\\r',
	      '\t': '\\t',
	      '\b': '\\b',
	      '\f': '\\f',
	      '\\': '\\\\',
	      '(': '\\(',
	      ')': '\\)'
	    };

	    swapBytes = function(buff) {
	      var a, i, l, _i, _ref;
	      l = buff.length;
	      if (l & 0x01) {
	        throw new Error("Buffer length must be even");
	      } else {
	        for (i = _i = 0, _ref = l - 1; _i < _ref; i = _i += 2) {
	          a = buff[i];
	          buff[i] = buff[i + 1];
	          buff[i + 1] = a;
	        }
	      }
	      return buff;
	    };

	    PDFObject.convert = function(object) {
	      var e, i, isUnicode, items, key, out, string, val, _i, _ref;
	      if (typeof object === 'string') {
	        return '/' + object;
	      } else if (object instanceof String) {
	        string = object.replace(escapableRe, function(c) {
	          return escapable[c];
	        });
	        isUnicode = false;
	        for (i = _i = 0, _ref = string.length; _i < _ref; i = _i += 1) {
	          if (string.charCodeAt(i) > 0x7f) {
	            isUnicode = true;
	            break;
	          }
	        }
	        if (isUnicode) {
	          string = swapBytes(new Buffer('\ufeff' + string, 'utf16le')).toString('binary');
	        }
	        return '(' + string + ')';
	      } else if (Buffer.isBuffer(object)) {
	        return '<' + object.toString('hex') + '>';
	      } else if (object instanceof PDFReference) {
	        return object.toString();
	      } else if (object instanceof Date) {
	        return '(D:' + pad(object.getUTCFullYear(), 4) + pad(object.getUTCMonth(), 2) + pad(object.getUTCDate(), 2) + pad(object.getUTCHours(), 2) + pad(object.getUTCMinutes(), 2) + pad(object.getUTCSeconds(), 2) + 'Z)';
	      } else if (Array.isArray(object)) {
	        items = ((function() {
	          var _j, _len, _results;
	          _results = [];
	          for (_j = 0, _len = object.length; _j < _len; _j++) {
	            e = object[_j];
	            _results.push(PDFObject.convert(e));
	          }
	          return _results;
	        })()).join(' ');
	        return '[' + items + ']';
	      } else if ({}.toString.call(object) === '[object Object]') {
	        out = ['<<'];
	        for (key in object) {
	          val = object[key];
	          out.push('/' + key + ' ' + PDFObject.convert(val));
	        }
	        out.push('>>');
	        return out.join('\n');
	      } else {
	        return '' + object;
	      }
	    };

	    return PDFObject;

	  })();

	  module.exports = PDFObject;

	  PDFReference = __webpack_require__(12);

	}).call(this);

	/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(4).Buffer))

/***/ },
/* 33 */
/***/ function(module, exports, __webpack_require__) {

	/* jslint node: true */
	'use strict';
	/*jshint -W004 */
	/* qr.js -- QR code generator in Javascript (revision 2011-01-19)
	 * Written by Kang Seonghoon <public+qrjs@mearie.org>.
	 *
	 * This source code is in the public domain; if your jurisdiction does not
	 * recognize the public domain the terms of Creative Commons CC0 license
	 * apply. In the other words, you can always do what you want.
	 */


	// per-version information (cf. JIS X 0510:2004 pp. 30--36, 71)
	//
	// [0]: the degree of generator polynomial by ECC levels
	// [1]: # of code blocks by ECC levels
	// [2]: left-top positions of alignment patterns
	//
	// the number in this table (in particular, [0]) does not exactly match with
	// the numbers in the specficiation. see augumenteccs below for the reason.
	var VERSIONS = [
		null,
		[[10, 7,17,13], [ 1, 1, 1, 1], []],
		[[16,10,28,22], [ 1, 1, 1, 1], [4,16]],
		[[26,15,22,18], [ 1, 1, 2, 2], [4,20]],
		[[18,20,16,26], [ 2, 1, 4, 2], [4,24]],
		[[24,26,22,18], [ 2, 1, 4, 4], [4,28]],
		[[16,18,28,24], [ 4, 2, 4, 4], [4,32]],
		[[18,20,26,18], [ 4, 2, 5, 6], [4,20,36]],
		[[22,24,26,22], [ 4, 2, 6, 6], [4,22,40]],
		[[22,30,24,20], [ 5, 2, 8, 8], [4,24,44]],
		[[26,18,28,24], [ 5, 4, 8, 8], [4,26,48]],
		[[30,20,24,28], [ 5, 4,11, 8], [4,28,52]],
		[[22,24,28,26], [ 8, 4,11,10], [4,30,56]],
		[[22,26,22,24], [ 9, 4,16,12], [4,32,60]],
		[[24,30,24,20], [ 9, 4,16,16], [4,24,44,64]],
		[[24,22,24,30], [10, 6,18,12], [4,24,46,68]],
		[[28,24,30,24], [10, 6,16,17], [4,24,48,72]],
		[[28,28,28,28], [11, 6,19,16], [4,28,52,76]],
		[[26,30,28,28], [13, 6,21,18], [4,28,54,80]],
		[[26,28,26,26], [14, 7,25,21], [4,28,56,84]],
		[[26,28,28,30], [16, 8,25,20], [4,32,60,88]],
		[[26,28,30,28], [17, 8,25,23], [4,26,48,70,92]],
		[[28,28,24,30], [17, 9,34,23], [4,24,48,72,96]],
		[[28,30,30,30], [18, 9,30,25], [4,28,52,76,100]],
		[[28,30,30,30], [20,10,32,27], [4,26,52,78,104]],
		[[28,26,30,30], [21,12,35,29], [4,30,56,82,108]],
		[[28,28,30,28], [23,12,37,34], [4,28,56,84,112]],
		[[28,30,30,30], [25,12,40,34], [4,32,60,88,116]],
		[[28,30,30,30], [26,13,42,35], [4,24,48,72,96,120]],
		[[28,30,30,30], [28,14,45,38], [4,28,52,76,100,124]],
		[[28,30,30,30], [29,15,48,40], [4,24,50,76,102,128]],
		[[28,30,30,30], [31,16,51,43], [4,28,54,80,106,132]],
		[[28,30,30,30], [33,17,54,45], [4,32,58,84,110,136]],
		[[28,30,30,30], [35,18,57,48], [4,28,56,84,112,140]],
		[[28,30,30,30], [37,19,60,51], [4,32,60,88,116,144]],
		[[28,30,30,30], [38,19,63,53], [4,28,52,76,100,124,148]],
		[[28,30,30,30], [40,20,66,56], [4,22,48,74,100,126,152]],
		[[28,30,30,30], [43,21,70,59], [4,26,52,78,104,130,156]],
		[[28,30,30,30], [45,22,74,62], [4,30,56,82,108,134,160]],
		[[28,30,30,30], [47,24,77,65], [4,24,52,80,108,136,164]],
		[[28,30,30,30], [49,25,81,68], [4,28,56,84,112,140,168]]];

	// mode constants (cf. Table 2 in JIS X 0510:2004 p. 16)
	var MODE_TERMINATOR = 0;
	var MODE_NUMERIC = 1, MODE_ALPHANUMERIC = 2, MODE_OCTET = 4, MODE_KANJI = 8;

	// validation regexps
	var NUMERIC_REGEXP = /^\d*$/;
	var ALPHANUMERIC_REGEXP = /^[A-Za-z0-9 $%*+\-./:]*$/;
	var ALPHANUMERIC_OUT_REGEXP = /^[A-Z0-9 $%*+\-./:]*$/;

	// ECC levels (cf. Table 22 in JIS X 0510:2004 p. 45)
	var ECCLEVEL_L = 1, ECCLEVEL_M = 0, ECCLEVEL_Q = 3, ECCLEVEL_H = 2;

	// GF(2^8)-to-integer mapping with a reducing polynomial x^8+x^4+x^3+x^2+1
	// invariant: GF256_MAP[GF256_INVMAP[i]] == i for all i in [1,256)
	var GF256_MAP = [], GF256_INVMAP = [-1];
	for (var i = 0, v = 1; i < 255; ++i) {
		GF256_MAP.push(v);
		GF256_INVMAP[v] = i;
		v = (v * 2) ^ (v >= 128 ? 0x11d : 0);
	}

	// generator polynomials up to degree 30
	// (should match with polynomials in JIS X 0510:2004 Appendix A)
	//
	// generator polynomial of degree K is product of (x-\alpha^0), (x-\alpha^1),
	// ..., (x-\alpha^(K-1)). by convention, we omit the K-th coefficient (always 1)
	// from the result; also other coefficients are written in terms of the exponent
	// to \alpha to avoid the redundant calculation. (see also calculateecc below.)
	var GF256_GENPOLY = [[]];
	for (var i = 0; i < 30; ++i) {
		var prevpoly = GF256_GENPOLY[i], poly = [];
		for (var j = 0; j <= i; ++j) {
			var a = (j < i ? GF256_MAP[prevpoly[j]] : 0);
			var b = GF256_MAP[(i + (prevpoly[j-1] || 0)) % 255];
			poly.push(GF256_INVMAP[a ^ b]);
		}
		GF256_GENPOLY.push(poly);
	}

	// alphanumeric character mapping (cf. Table 5 in JIS X 0510:2004 p. 19)
	var ALPHANUMERIC_MAP = {};
	for (var i = 0; i < 45; ++i) {
		ALPHANUMERIC_MAP['0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ $%*+-./:'.charAt(i)] = i;
	}

	// mask functions in terms of row # and column #
	// (cf. Table 20 in JIS X 0510:2004 p. 42)
	var MASKFUNCS = [
		function(i,j) { return (i+j) % 2 === 0; },
		function(i,j) { return i % 2 === 0; },
		function(i,j) { return j % 3 === 0; },
		function(i,j) { return (i+j) % 3 === 0; },
		function(i,j) { return (((i/2)|0) + ((j/3)|0)) % 2 === 0; },
		function(i,j) { return (i*j) % 2 + (i*j) % 3 === 0; },
		function(i,j) { return ((i*j) % 2 + (i*j) % 3) % 2 === 0; },
		function(i,j) { return ((i+j) % 2 + (i*j) % 3) % 2 === 0; }];

	// returns true when the version information has to be embeded.
	var needsverinfo = function(ver) { return ver > 6; };

	// returns the size of entire QR code for given version.
	var getsizebyver = function(ver) { return 4 * ver + 17; };

	// returns the number of bits available for code words in this version.
	var nfullbits = function(ver) {
		/*
		 * |<--------------- n --------------->|
		 * |        |<----- n-17 ---->|        |
		 * +-------+                ///+-------+ ----
		 * |       |                ///|       |    ^
		 * |  9x9  |       @@@@@    ///|  9x8  |    |
		 * |       | # # # @5x5@ # # # |       |    |
		 * +-------+       @@@@@       +-------+    |
		 *       #                               ---|
		 *                                        ^ |
		 *       #                                |
		 *     @@@@@       @@@@@       @@@@@      | n
		 *     @5x5@       @5x5@       @5x5@   n-17
		 *     @@@@@       @@@@@       @@@@@      | |
		 *       #                                | |
		 * //////                                 v |
		 * //////#                               ---|
		 * +-------+       @@@@@       @@@@@        |
		 * |       |       @5x5@       @5x5@        |
		 * |  8x9  |       @@@@@       @@@@@        |
		 * |       |                                v
		 * +-------+                             ----
		 *
		 * when the entire code has n^2 modules and there are m^2-3 alignment
		 * patterns, we have:
		 * - 225 (= 9x9 + 9x8 + 8x9) modules for finder patterns and
		 *   format information;
		 * - 2n-34 (= 2(n-17)) modules for timing patterns;
		 * - 36 (= 3x6 + 6x3) modules for version information, if any;
		 * - 25m^2-75 (= (m^2-3)(5x5)) modules for alignment patterns
		 *   if any, but 10m-20 (= 2(m-2)x5) of them overlaps with
		 *   timing patterns.
		 */
		var v = VERSIONS[ver];
		var nbits = 16*ver*ver + 128*ver + 64; // finder, timing and format info.
		if (needsverinfo(ver)) nbits -= 36; // version information
		if (v[2].length) { // alignment patterns
			nbits -= 25 * v[2].length * v[2].length - 10 * v[2].length - 55;
		}
		return nbits;
	};

	// returns the number of bits available for data portions (i.e. excludes ECC
	// bits but includes mode and length bits) in this version and ECC level.
	var ndatabits = function(ver, ecclevel) {
		var nbits = nfullbits(ver) & ~7; // no sub-octet code words
		var v = VERSIONS[ver];
		nbits -= 8 * v[0][ecclevel] * v[1][ecclevel]; // ecc bits
		return nbits;
	};

	// returns the number of bits required for the length of data.
	// (cf. Table 3 in JIS X 0510:2004 p. 16)
	var ndatalenbits = function(ver, mode) {
		switch (mode) {
		case MODE_NUMERIC: return (ver < 10 ? 10 : ver < 27 ? 12 : 14);
		case MODE_ALPHANUMERIC: return (ver < 10 ? 9 : ver < 27 ? 11 : 13);
		case MODE_OCTET: return (ver < 10 ? 8 : 16);
		case MODE_KANJI: return (ver < 10 ? 8 : ver < 27 ? 10 : 12);
		}
	};

	// returns the maximum length of data possible in given configuration.
	var getmaxdatalen = function(ver, mode, ecclevel) {
		var nbits = ndatabits(ver, ecclevel) - 4 - ndatalenbits(ver, mode); // 4 for mode bits
		switch (mode) {
		case MODE_NUMERIC:
			return ((nbits/10) | 0) * 3 + (nbits%10 < 4 ? 0 : nbits%10 < 7 ? 1 : 2);
		case MODE_ALPHANUMERIC:
			return ((nbits/11) | 0) * 2 + (nbits%11 < 6 ? 0 : 1);
		case MODE_OCTET:
			return (nbits/8) | 0;
		case MODE_KANJI:
			return (nbits/13) | 0;
		}
	};

	// checks if the given data can be encoded in given mode, and returns
	// the converted data for the further processing if possible. otherwise
	// returns null.
	//
	// this function does not check the length of data; it is a duty of
	// encode function below (as it depends on the version and ECC level too).
	var validatedata = function(mode, data) {
		switch (mode) {
		case MODE_NUMERIC:
			if (!data.match(NUMERIC_REGEXP)) return null;
			return data;

		case MODE_ALPHANUMERIC:
			if (!data.match(ALPHANUMERIC_REGEXP)) return null;
			return data.toUpperCase();

		case MODE_OCTET:
			if (typeof data === 'string') { // encode as utf-8 string
				var newdata = [];
				for (var i = 0; i < data.length; ++i) {
					var ch = data.charCodeAt(i);
					if (ch < 0x80) {
						newdata.push(ch);
					} else if (ch < 0x800) {
						newdata.push(0xc0 | (ch >> 6),
							0x80 | (ch & 0x3f));
					} else if (ch < 0x10000) {
						newdata.push(0xe0 | (ch >> 12),
							0x80 | ((ch >> 6) & 0x3f),
							0x80 | (ch & 0x3f));
					} else {
						newdata.push(0xf0 | (ch >> 18),
							0x80 | ((ch >> 12) & 0x3f),
							0x80 | ((ch >> 6) & 0x3f),
							0x80 | (ch & 0x3f));
					}
				}
				return newdata;
			} else {
				return data;
			}
		}
	};

	// returns the code words (sans ECC bits) for given data and configurations.
	// requires data to be preprocessed by validatedata. no length check is
	// performed, and everything has to be checked before calling this function.
	var encode = function(ver, mode, data, maxbuflen) {
		var buf = [];
		var bits = 0, remaining = 8;
		var datalen = data.length;

		// this function is intentionally no-op when n=0.
		var pack = function(x, n) {
			if (n >= remaining) {
				buf.push(bits | (x >> (n -= remaining)));
				while (n >= 8) buf.push((x >> (n -= 8)) & 255);
				bits = 0;
				remaining = 8;
			}
			if (n > 0) bits |= (x & ((1 << n) - 1)) << (remaining -= n);
		};

		var nlenbits = ndatalenbits(ver, mode);
		pack(mode, 4);
		pack(datalen, nlenbits);

		switch (mode) {
		case MODE_NUMERIC:
			for (var i = 2; i < datalen; i += 3) {
				pack(parseInt(data.substring(i-2,i+1), 10), 10);
			}
			pack(parseInt(data.substring(i-2), 10), [0,4,7][datalen%3]);
			break;

		case MODE_ALPHANUMERIC:
			for (var i = 1; i < datalen; i += 2) {
				pack(ALPHANUMERIC_MAP[data.charAt(i-1)] * 45 +
					ALPHANUMERIC_MAP[data.charAt(i)], 11);
			}
			if (datalen % 2 == 1) {
				pack(ALPHANUMERIC_MAP[data.charAt(i-1)], 6);
			}
			break;

		case MODE_OCTET:
			for (var i = 0; i < datalen; ++i) {
				pack(data[i], 8);
			}
			break;
		}

		// final bits. it is possible that adding terminator causes the buffer
		// to overflow, but then the buffer truncated to the maximum size will
		// be valid as the truncated terminator mode bits and padding is
		// identical in appearance (cf. JIS X 0510:2004 sec 8.4.8).
		pack(MODE_TERMINATOR, 4);
		if (remaining < 8) buf.push(bits);

		// the padding to fill up the remaining space. we should not add any
		// words when the overflow already occurred.
		while (buf.length + 1 < maxbuflen) buf.push(0xec, 0x11);
		if (buf.length < maxbuflen) buf.push(0xec);
		return buf;
	};

	// calculates ECC code words for given code words and generator polynomial.
	//
	// this is quite similar to CRC calculation as both Reed-Solomon and CRC use
	// the certain kind of cyclic codes, which is effectively the division of
	// zero-augumented polynomial by the generator polynomial. the only difference
	// is that Reed-Solomon uses GF(2^8), instead of CRC's GF(2), and Reed-Solomon
	// uses the different generator polynomial than CRC's.
	var calculateecc = function(poly, genpoly) {
		var modulus = poly.slice(0);
		var polylen = poly.length, genpolylen = genpoly.length;
		for (var i = 0; i < genpolylen; ++i) modulus.push(0);
		for (var i = 0; i < polylen; ) {
			var quotient = GF256_INVMAP[modulus[i++]];
			if (quotient >= 0) {
				for (var j = 0; j < genpolylen; ++j) {
					modulus[i+j] ^= GF256_MAP[(quotient + genpoly[j]) % 255];
				}
			}
		}
		return modulus.slice(polylen);
	};

	// auguments ECC code words to given code words. the resulting words are
	// ready to be encoded in the matrix.
	//
	// the much of actual augumenting procedure follows JIS X 0510:2004 sec 8.7.
	// the code is simplified using the fact that the size of each code & ECC
	// blocks is almost same; for example, when we have 4 blocks and 46 data words
	// the number of code words in those blocks are 11, 11, 12, 12 respectively.
	var augumenteccs = function(poly, nblocks, genpoly) {
		var subsizes = [];
		var subsize = (poly.length / nblocks) | 0, subsize0 = 0;
		var pivot = nblocks - poly.length % nblocks;
		for (var i = 0; i < pivot; ++i) {
			subsizes.push(subsize0);
			subsize0 += subsize;
		}
		for (var i = pivot; i < nblocks; ++i) {
			subsizes.push(subsize0);
			subsize0 += subsize+1;
		}
		subsizes.push(subsize0);

		var eccs = [];
		for (var i = 0; i < nblocks; ++i) {
			eccs.push(calculateecc(poly.slice(subsizes[i], subsizes[i+1]), genpoly));
		}

		var result = [];
		var nitemsperblock = (poly.length / nblocks) | 0;
		for (var i = 0; i < nitemsperblock; ++i) {
			for (var j = 0; j < nblocks; ++j) {
				result.push(poly[subsizes[j] + i]);
			}
		}
		for (var j = pivot; j < nblocks; ++j) {
			result.push(poly[subsizes[j+1] - 1]);
		}
		for (var i = 0; i < genpoly.length; ++i) {
			for (var j = 0; j < nblocks; ++j) {
				result.push(eccs[j][i]);
			}
		}
		return result;
	};

	// auguments BCH(p+q,q) code to the polynomial over GF(2), given the proper
	// genpoly. the both input and output are in binary numbers, and unlike
	// calculateecc genpoly should include the 1 bit for the highest degree.
	//
	// actual polynomials used for this procedure are as follows:
	// - p=10, q=5, genpoly=x^10+x^8+x^5+x^4+x^2+x+1 (JIS X 0510:2004 Appendix C)
	// - p=18, q=6, genpoly=x^12+x^11+x^10+x^9+x^8+x^5+x^2+1 (ibid. Appendix D)
	var augumentbch = function(poly, p, genpoly, q) {
		var modulus = poly << q;
		for (var i = p - 1; i >= 0; --i) {
			if ((modulus >> (q+i)) & 1) modulus ^= genpoly << i;
		}
		return (poly << q) | modulus;
	};

	// creates the base matrix for given version. it returns two matrices, one of
	// them is the actual one and the another represents the "reserved" portion
	// (e.g. finder and timing patterns) of the matrix.
	//
	// some entries in the matrix may be undefined, rather than 0 or 1. this is
	// intentional (no initialization needed!), and putdata below will fill
	// the remaining ones.
	var makebasematrix = function(ver) {
		var v = VERSIONS[ver], n = getsizebyver(ver);
		var matrix = [], reserved = [];
		for (var i = 0; i < n; ++i) {
			matrix.push([]);
			reserved.push([]);
		}

		var blit = function(y, x, h, w, bits) {
			for (var i = 0; i < h; ++i) {
				for (var j = 0; j < w; ++j) {
					matrix[y+i][x+j] = (bits[i] >> j) & 1;
					reserved[y+i][x+j] = 1;
				}
			}
		};

		// finder patterns and a part of timing patterns
		// will also mark the format information area (not yet written) as reserved.
		blit(0, 0, 9, 9, [0x7f, 0x41, 0x5d, 0x5d, 0x5d, 0x41, 0x17f, 0x00, 0x40]);
		blit(n-8, 0, 8, 9, [0x100, 0x7f, 0x41, 0x5d, 0x5d, 0x5d, 0x41, 0x7f]);
		blit(0, n-8, 9, 8, [0xfe, 0x82, 0xba, 0xba, 0xba, 0x82, 0xfe, 0x00, 0x00]);

		// the rest of timing patterns
		for (var i = 9; i < n-8; ++i) {
			matrix[6][i] = matrix[i][6] = ~i & 1;
			reserved[6][i] = reserved[i][6] = 1;
		}

		// alignment patterns
		var aligns = v[2], m = aligns.length;
		for (var i = 0; i < m; ++i) {
			var minj = (i===0 || i===m-1 ? 1 : 0), maxj = (i===0 ? m-1 : m);
			for (var j = minj; j < maxj; ++j) {
				blit(aligns[i], aligns[j], 5, 5, [0x1f, 0x11, 0x15, 0x11, 0x1f]);
			}
		}

		// version information
		if (needsverinfo(ver)) {
			var code = augumentbch(ver, 6, 0x1f25, 12);
			var k = 0;
			for (var i = 0; i < 6; ++i) {
				for (var j = 0; j < 3; ++j) {
					matrix[i][(n-11)+j] = matrix[(n-11)+j][i] = (code >> k++) & 1;
					reserved[i][(n-11)+j] = reserved[(n-11)+j][i] = 1;
				}
			}
		}

		return {matrix: matrix, reserved: reserved};
	};

	// fills the data portion (i.e. unmarked in reserved) of the matrix with given
	// code words. the size of code words should be no more than available bits,
	// and remaining bits are padded to 0 (cf. JIS X 0510:2004 sec 8.7.3).
	var putdata = function(matrix, reserved, buf) {
		var n = matrix.length;
		var k = 0, dir = -1;
		for (var i = n-1; i >= 0; i -= 2) {
			if (i == 6) --i; // skip the entire timing pattern column
			var jj = (dir < 0 ? n-1 : 0);
			for (var j = 0; j < n; ++j) {
				for (var ii = i; ii > i-2; --ii) {
					if (!reserved[jj][ii]) {
						// may overflow, but (undefined >> x)
						// is 0 so it will auto-pad to zero.
						matrix[jj][ii] = (buf[k >> 3] >> (~k&7)) & 1;
						++k;
					}
				}
				jj += dir;
			}
			dir = -dir;
		}
		return matrix;
	};

	// XOR-masks the data portion of the matrix. repeating the call with the same
	// arguments will revert the prior call (convenient in the matrix evaluation).
	var maskdata = function(matrix, reserved, mask) {
		var maskf = MASKFUNCS[mask];
		var n = matrix.length;
		for (var i = 0; i < n; ++i) {
			for (var j = 0; j < n; ++j) {
				if (!reserved[i][j]) matrix[i][j] ^= maskf(i,j);
			}
		}
		return matrix;
	};

	// puts the format information.
	var putformatinfo = function(matrix, reserved, ecclevel, mask) {
		var n = matrix.length;
		var code = augumentbch((ecclevel << 3) | mask, 5, 0x537, 10) ^ 0x5412;
		for (var i = 0; i < 15; ++i) {
			var r = [0,1,2,3,4,5,7,8,n-7,n-6,n-5,n-4,n-3,n-2,n-1][i];
			var c = [n-1,n-2,n-3,n-4,n-5,n-6,n-7,n-8,7,5,4,3,2,1,0][i];
			matrix[r][8] = matrix[8][c] = (code >> i) & 1;
			// we don't have to mark those bits reserved; always done
			// in makebasematrix above.
		}
		return matrix;
	};

	// evaluates the resulting matrix and returns the score (lower is better).
	// (cf. JIS X 0510:2004 sec 8.8.2)
	//
	// the evaluation procedure tries to avoid the problematic patterns naturally
	// occuring from the original matrix. for example, it penaltizes the patterns
	// which just look like the finder pattern which will confuse the decoder.
	// we choose the mask which results in the lowest score among 8 possible ones.
	//
	// note: zxing seems to use the same procedure and in many cases its choice
	// agrees to ours, but sometimes it does not. practically it doesn't matter.
	var evaluatematrix = function(matrix) {
		// N1+(k-5) points for each consecutive row of k same-colored modules,
		// where k >= 5. no overlapping row counts.
		var PENALTY_CONSECUTIVE = 3;
		// N2 points for each 2x2 block of same-colored modules.
		// overlapping block does count.
		var PENALTY_TWOBYTWO = 3;
		// N3 points for each pattern with >4W:1B:1W:3B:1W:1B or
		// 1B:1W:3B:1W:1B:>4W, or their multiples (e.g. highly unlikely,
		// but 13W:3B:3W:9B:3W:3B counts).
		var PENALTY_FINDERLIKE = 40;
		// N4*k points for every (5*k)% deviation from 50% black density.
		// i.e. k=1 for 55~60% and 40~45%, k=2 for 60~65% and 35~40%, etc.
		var PENALTY_DENSITY = 10;

		var evaluategroup = function(groups) { // assumes [W,B,W,B,W,...,B,W]
			var score = 0;
			for (var i = 0; i < groups.length; ++i) {
				if (groups[i] >= 5) score += PENALTY_CONSECUTIVE + (groups[i]-5);
			}
			for (var i = 5; i < groups.length; i += 2) {
				var p = groups[i];
				if (groups[i-1] == p && groups[i-2] == 3*p && groups[i-3] == p &&
						groups[i-4] == p && (groups[i-5] >= 4*p || groups[i+1] >= 4*p)) {
					// this part differs from zxing...
					score += PENALTY_FINDERLIKE;
				}
			}
			return score;
		};

		var n = matrix.length;
		var score = 0, nblacks = 0;
		for (var i = 0; i < n; ++i) {
			var row = matrix[i];
			var groups;

			// evaluate the current row
			groups = [0]; // the first empty group of white
			for (var j = 0; j < n; ) {
				var k;
				for (k = 0; j < n && row[j]; ++k) ++j;
				groups.push(k);
				for (k = 0; j < n && !row[j]; ++k) ++j;
				groups.push(k);
			}
			score += evaluategroup(groups);

			// evaluate the current column
			groups = [0];
			for (var j = 0; j < n; ) {
				var k;
				for (k = 0; j < n && matrix[j][i]; ++k) ++j;
				groups.push(k);
				for (k = 0; j < n && !matrix[j][i]; ++k) ++j;
				groups.push(k);
			}
			score += evaluategroup(groups);

			// check the 2x2 box and calculate the density
			var nextrow = matrix[i+1] || [];
			nblacks += row[0];
			for (var j = 1; j < n; ++j) {
				var p = row[j];
				nblacks += p;
				// at least comparison with next row should be strict...
				if (row[j-1] == p && nextrow[j] === p && nextrow[j-1] === p) {
					score += PENALTY_TWOBYTWO;
				}
			}
		}

		score += PENALTY_DENSITY * ((Math.abs(nblacks / n / n - 0.5) / 0.05) | 0);
		return score;
	};

	// returns the fully encoded QR code matrix which contains given data.
	// it also chooses the best mask automatically when mask is -1.
	var generate = function(data, ver, mode, ecclevel, mask) {
		var v = VERSIONS[ver];
		var buf = encode(ver, mode, data, ndatabits(ver, ecclevel) >> 3);
		buf = augumenteccs(buf, v[1][ecclevel], GF256_GENPOLY[v[0][ecclevel]]);

		var result = makebasematrix(ver);
		var matrix = result.matrix, reserved = result.reserved;
		putdata(matrix, reserved, buf);

		if (mask < 0) {
			// find the best mask
			maskdata(matrix, reserved, 0);
			putformatinfo(matrix, reserved, ecclevel, 0);
			var bestmask = 0, bestscore = evaluatematrix(matrix);
			maskdata(matrix, reserved, 0);
			for (mask = 1; mask < 8; ++mask) {
				maskdata(matrix, reserved, mask);
				putformatinfo(matrix, reserved, ecclevel, mask);
				var score = evaluatematrix(matrix);
				if (bestscore > score) {
					bestscore = score;
					bestmask = mask;
				}
				maskdata(matrix, reserved, mask);
			}
			mask = bestmask;
		}

		maskdata(matrix, reserved, mask);
		putformatinfo(matrix, reserved, ecclevel, mask);
		return matrix;
	};

	// the public interface is trivial; the options available are as follows:
	//
	// - version: an integer in [1,40]. when omitted (or -1) the smallest possible
	//   version is chosen.
	// - mode: one of 'numeric', 'alphanumeric', 'octet'. when omitted the smallest
	//   possible mode is chosen.
	// - eccLevel: one of 'L', 'M', 'Q', 'H'. defaults to 'L'.
	// - mask: an integer in [0,7]. when omitted (or -1) the best mask is chosen.
	//

	function generateFrame(data, options) {
			var MODES = {'numeric': MODE_NUMERIC, 'alphanumeric': MODE_ALPHANUMERIC,
				'octet': MODE_OCTET};
			var ECCLEVELS = {'L': ECCLEVEL_L, 'M': ECCLEVEL_M, 'Q': ECCLEVEL_Q,
				'H': ECCLEVEL_H};

			options = options || {};
			var ver = options.version || -1;
			var ecclevel = ECCLEVELS[(options.eccLevel || 'L').toUpperCase()];
			var mode = options.mode ? MODES[options.mode.toLowerCase()] : -1;
			var mask = 'mask' in options ? options.mask : -1;

			if (mode < 0) {
				if (typeof data === 'string') {
					if (data.match(NUMERIC_REGEXP)) {
						mode = MODE_NUMERIC;
					} else if (data.match(ALPHANUMERIC_OUT_REGEXP)) {
						// while encode supports case-insensitive encoding, we restrict the data to be uppercased when auto-selecting the mode.
						mode = MODE_ALPHANUMERIC;
					} else {
						mode = MODE_OCTET;
					}
				} else {
					mode = MODE_OCTET;
				}
			} else if (!(mode == MODE_NUMERIC || mode == MODE_ALPHANUMERIC ||
					mode == MODE_OCTET)) {
				throw 'invalid or unsupported mode';
			}

			data = validatedata(mode, data);
			if (data === null) throw 'invalid data format';

			if (ecclevel < 0 || ecclevel > 3) throw 'invalid ECC level';

			if (ver < 0) {
				for (ver = 1; ver <= 40; ++ver) {
					if (data.length <= getmaxdatalen(ver, mode, ecclevel)) break;
				}
				if (ver > 40) throw 'too large data for the Qr format';
			} else if (ver < 1 || ver > 40) {
				throw 'invalid Qr version! should be between 1 and 40';
			}

			if (mask != -1 && (mask < 0 || mask > 8)) throw 'invalid mask';
	        //console.log('version:', ver, 'mode:', mode, 'ECC:', ecclevel, 'mask:', mask )
			return generate(data, ver, mode, ecclevel, mask);
		}


	// options
	// - modulesize: a number. this is a size of each modules in pixels, and
	//   defaults to 5px.
	// - margin: a number. this is a size of margin in *modules*, and defaults to
	//   4 (white modules). the specficiation mandates the margin no less than 4
	//   modules, so it is better not to alter this value unless you know what
	//   you're doing.
	function buildCanvas(data, options) {
	   
	    var canvas = [];
	    var background = data.background || '#fff';
	    var foreground = data.foreground || '#000';
	    //var margin = options.margin || 4;
		var matrix = generateFrame(data, options);
		var n = matrix.length;
		var modSize = Math.floor( options.fit ? options.fit/n : 5 );
		var size = n * modSize;
		
	    canvas.push({
	      type: 'rect',
	      x: 0, y: 0, w: size, h: size, lineWidth: 0, color: background
	    });
	    
		for (var i = 0; i < n; ++i) {
			for (var j = 0; j < n; ++j) {
	            if(matrix[i][j]) {
	              canvas.push({
	                type: 'rect',
	                x: modSize * i,
	                y: modSize * j,
	                w: modSize,
	                h: modSize,
	                lineWidth: 0,
	                color: foreground
	              });
	            }
	        }
	    }
	    
	    return {
	        canvas: canvas,
	        size: size
	    };
			
	}

	function measure(node) {
	    var cd = buildCanvas(node.qr, node);
	    node._canvas = cd.canvas;
	    node._width = node._height = node._minWidth = node._maxWidth = node._minHeight = node._maxHeight = cd.size;
	    return node;
	}

	module.exports = {
	  measure: measure
	};

/***/ },
/* 34 */
/***/ function(module, exports, __webpack_require__) {

	// Generated by CoffeeScript 1.7.1
	(function() {
	  var Data;

	  Data = (function() {
	    function Data(data) {
	      this.data = data != null ? data : [];
	      this.pos = 0;
	      this.length = this.data.length;
	    }

	    Data.prototype.readByte = function() {
	      return this.data[this.pos++];
	    };

	    Data.prototype.writeByte = function(byte) {
	      return this.data[this.pos++] = byte;
	    };

	    Data.prototype.byteAt = function(index) {
	      return this.data[index];
	    };

	    Data.prototype.readBool = function() {
	      return !!this.readByte();
	    };

	    Data.prototype.writeBool = function(val) {
	      return this.writeByte(val ? 1 : 0);
	    };

	    Data.prototype.readUInt32 = function() {
	      var b1, b2, b3, b4;
	      b1 = this.readByte() * 0x1000000;
	      b2 = this.readByte() << 16;
	      b3 = this.readByte() << 8;
	      b4 = this.readByte();
	      return b1 + b2 + b3 + b4;
	    };

	    Data.prototype.writeUInt32 = function(val) {
	      this.writeByte((val >>> 24) & 0xff);
	      this.writeByte((val >> 16) & 0xff);
	      this.writeByte((val >> 8) & 0xff);
	      return this.writeByte(val & 0xff);
	    };

	    Data.prototype.readInt32 = function() {
	      var int;
	      int = this.readUInt32();
	      if (int >= 0x80000000) {
	        return int - 0x100000000;
	      } else {
	        return int;
	      }
	    };

	    Data.prototype.writeInt32 = function(val) {
	      if (val < 0) {
	        val += 0x100000000;
	      }
	      return this.writeUInt32(val);
	    };

	    Data.prototype.readUInt16 = function() {
	      var b1, b2;
	      b1 = this.readByte() << 8;
	      b2 = this.readByte();
	      return b1 | b2;
	    };

	    Data.prototype.writeUInt16 = function(val) {
	      this.writeByte((val >> 8) & 0xff);
	      return this.writeByte(val & 0xff);
	    };

	    Data.prototype.readInt16 = function() {
	      var int;
	      int = this.readUInt16();
	      if (int >= 0x8000) {
	        return int - 0x10000;
	      } else {
	        return int;
	      }
	    };

	    Data.prototype.writeInt16 = function(val) {
	      if (val < 0) {
	        val += 0x10000;
	      }
	      return this.writeUInt16(val);
	    };

	    Data.prototype.readString = function(length) {
	      var i, ret, _i;
	      ret = [];
	      for (i = _i = 0; 0 <= length ? _i < length : _i > length; i = 0 <= length ? ++_i : --_i) {
	        ret[i] = String.fromCharCode(this.readByte());
	      }
	      return ret.join('');
	    };

	    Data.prototype.writeString = function(val) {
	      var i, _i, _ref, _results;
	      _results = [];
	      for (i = _i = 0, _ref = val.length; 0 <= _ref ? _i < _ref : _i > _ref; i = 0 <= _ref ? ++_i : --_i) {
	        _results.push(this.writeByte(val.charCodeAt(i)));
	      }
	      return _results;
	    };

	    Data.prototype.stringAt = function(pos, length) {
	      this.pos = pos;
	      return this.readString(length);
	    };

	    Data.prototype.readShort = function() {
	      return this.readInt16();
	    };

	    Data.prototype.writeShort = function(val) {
	      return this.writeInt16(val);
	    };

	    Data.prototype.readLongLong = function() {
	      var b1, b2, b3, b4, b5, b6, b7, b8;
	      b1 = this.readByte();
	      b2 = this.readByte();
	      b3 = this.readByte();
	      b4 = this.readByte();
	      b5 = this.readByte();
	      b6 = this.readByte();
	      b7 = this.readByte();
	      b8 = this.readByte();
	      if (b1 & 0x80) {
	        return ((b1 ^ 0xff) * 0x100000000000000 + (b2 ^ 0xff) * 0x1000000000000 + (b3 ^ 0xff) * 0x10000000000 + (b4 ^ 0xff) * 0x100000000 + (b5 ^ 0xff) * 0x1000000 + (b6 ^ 0xff) * 0x10000 + (b7 ^ 0xff) * 0x100 + (b8 ^ 0xff) + 1) * -1;
	      }
	      return b1 * 0x100000000000000 + b2 * 0x1000000000000 + b3 * 0x10000000000 + b4 * 0x100000000 + b5 * 0x1000000 + b6 * 0x10000 + b7 * 0x100 + b8;
	    };

	    Data.prototype.writeLongLong = function(val) {
	      var high, low;
	      high = Math.floor(val / 0x100000000);
	      low = val & 0xffffffff;
	      this.writeByte((high >> 24) & 0xff);
	      this.writeByte((high >> 16) & 0xff);
	      this.writeByte((high >> 8) & 0xff);
	      this.writeByte(high & 0xff);
	      this.writeByte((low >> 24) & 0xff);
	      this.writeByte((low >> 16) & 0xff);
	      this.writeByte((low >> 8) & 0xff);
	      return this.writeByte(low & 0xff);
	    };

	    Data.prototype.readInt = function() {
	      return this.readInt32();
	    };

	    Data.prototype.writeInt = function(val) {
	      return this.writeInt32(val);
	    };

	    Data.prototype.slice = function(start, end) {
	      return this.data.slice(start, end);
	    };

	    Data.prototype.read = function(bytes) {
	      var buf, i, _i;
	      buf = [];
	      for (i = _i = 0; 0 <= bytes ? _i < bytes : _i > bytes; i = 0 <= bytes ? ++_i : --_i) {
	        buf.push(this.readByte());
	      }
	      return buf;
	    };

	    Data.prototype.write = function(bytes) {
	      var byte, _i, _len, _results;
	      _results = [];
	      for (_i = 0, _len = bytes.length; _i < _len; _i++) {
	        byte = bytes[_i];
	        _results.push(this.writeByte(byte));
	      }
	      return _results;
	    };

	    return Data;

	  })();

	  module.exports = Data;

	}).call(this);


/***/ },
/* 35 */
/***/ function(module, exports, __webpack_require__) {

	// Generated by CoffeeScript 1.7.1
	(function() {
	  var JPEG, fs,
	    __indexOf = [].indexOf || function(item) { for (var i = 0, l = this.length; i < l; i++) { if (i in this && this[i] === item) return i; } return -1; };

	  fs = __webpack_require__(10);

	  JPEG = (function() {
	    var MARKERS;

	    MARKERS = [0xFFC0, 0xFFC1, 0xFFC2, 0xFFC3, 0xFFC5, 0xFFC6, 0xFFC7, 0xFFC8, 0xFFC9, 0xFFCA, 0xFFCB, 0xFFCC, 0xFFCD, 0xFFCE, 0xFFCF];

	    function JPEG(data, label) {
	      var channels, marker, pos;
	      this.data = data;
	      this.label = label;
	      if (this.data.readUInt16BE(0) !== 0xFFD8) {
	        throw "SOI not found in JPEG";
	      }
	      pos = 2;
	      while (pos < this.data.length) {
	        marker = this.data.readUInt16BE(pos);
	        pos += 2;
	        if (__indexOf.call(MARKERS, marker) >= 0) {
	          break;
	        }
	        pos += this.data.readUInt16BE(pos);
	      }
	      if (__indexOf.call(MARKERS, marker) < 0) {
	        throw "Invalid JPEG.";
	      }
	      pos += 2;
	      this.bits = this.data[pos++];
	      this.height = this.data.readUInt16BE(pos);
	      pos += 2;
	      this.width = this.data.readUInt16BE(pos);
	      pos += 2;
	      channels = this.data[pos++];
	      this.colorSpace = (function() {
	        switch (channels) {
	          case 1:
	            return 'DeviceGray';
	          case 3:
	            return 'DeviceRGB';
	          case 4:
	            return 'DeviceCMYK';
	        }
	      })();
	      this.obj = null;
	    }

	    JPEG.prototype.embed = function(document) {
	      if (this.obj) {
	        return;
	      }
	      this.obj = document.ref({
	        Type: 'XObject',
	        Subtype: 'Image',
	        BitsPerComponent: this.bits,
	        Width: this.width,
	        Height: this.height,
	        ColorSpace: this.colorSpace,
	        Filter: 'DCTDecode'
	      });
	      if (this.colorSpace === 'DeviceCMYK') {
	        this.obj.data['Decode'] = [1.0, 0.0, 1.0, 0.0, 1.0, 0.0, 1.0, 0.0];
	      }
	      this.obj.end(this.data);
	      return this.data = null;
	    };

	    return JPEG;

	  })();

	  module.exports = JPEG;

	}).call(this);


/***/ },
/* 36 */
/***/ function(module, exports, __webpack_require__) {

	/* WEBPACK VAR INJECTION */(function(Buffer) {// Generated by CoffeeScript 1.7.1
	(function() {
	  var PNG, PNGImage, zlib;

	  zlib = __webpack_require__(45);

	  PNG = __webpack_require__(51);

	  PNGImage = (function() {
	    function PNGImage(data, label) {
	      this.label = label;
	      this.image = new PNG(data);
	      this.width = this.image.width;
	      this.height = this.image.height;
	      this.imgData = this.image.imgData;
	      this.obj = null;
	    }

	    PNGImage.prototype.embed = function(document) {
	      var mask, palette, params, rgb, val, x, _i, _len;
	      this.document = document;
	      if (this.obj) {
	        return;
	      }
	      this.obj = document.ref({
	        Type: 'XObject',
	        Subtype: 'Image',
	        BitsPerComponent: this.image.bits,
	        Width: this.width,
	        Height: this.height,
	        Filter: 'FlateDecode'
	      });
	      if (!this.image.hasAlphaChannel) {
	        params = document.ref({
	          Predictor: 15,
	          Colors: this.image.colors,
	          BitsPerComponent: this.image.bits,
	          Columns: this.width
	        });
	        this.obj.data['DecodeParms'] = params;
	        params.end();
	      }
	      if (this.image.palette.length === 0) {
	        this.obj.data['ColorSpace'] = this.image.colorSpace;
	      } else {
	        palette = document.ref();
	        palette.end(new Buffer(this.image.palette));
	        this.obj.data['ColorSpace'] = ['Indexed', 'DeviceRGB', (this.image.palette.length / 3) - 1, palette];
	      }
	      if (this.image.transparency.grayscale) {
	        val = this.image.transparency.greyscale;
	        return this.obj.data['Mask'] = [val, val];
	      } else if (this.image.transparency.rgb) {
	        rgb = this.image.transparency.rgb;
	        mask = [];
	        for (_i = 0, _len = rgb.length; _i < _len; _i++) {
	          x = rgb[_i];
	          mask.push(x, x);
	        }
	        return this.obj.data['Mask'] = mask;
	      } else if (this.image.transparency.indexed) {
	        return this.loadIndexedAlphaChannel();
	      } else if (this.image.hasAlphaChannel) {
	        return this.splitAlphaChannel();
	      } else {
	        return this.finalize();
	      }
	    };

	    PNGImage.prototype.finalize = function() {
	      var sMask;
	      if (this.alphaChannel) {
	        sMask = this.document.ref({
	          Type: 'XObject',
	          Subtype: 'Image',
	          Height: this.height,
	          Width: this.width,
	          BitsPerComponent: 8,
	          Filter: 'FlateDecode',
	          ColorSpace: 'DeviceGray',
	          Decode: [0, 1]
	        });
	        sMask.end(this.alphaChannel);
	        this.obj.data['SMask'] = sMask;
	      }
	      this.obj.end(this.imgData);
	      this.image = null;
	      return this.imgData = null;
	    };

	    PNGImage.prototype.splitAlphaChannel = function() {
	      return this.image.decodePixels((function(_this) {
	        return function(pixels) {
	          var a, alphaChannel, colorByteSize, done, i, imgData, len, p, pixelCount;
	          colorByteSize = _this.image.colors * _this.image.bits / 8;
	          pixelCount = _this.width * _this.height;
	          imgData = new Buffer(pixelCount * colorByteSize);
	          alphaChannel = new Buffer(pixelCount);
	          i = p = a = 0;
	          len = pixels.length;
	          while (i < len) {
	            imgData[p++] = pixels[i++];
	            imgData[p++] = pixels[i++];
	            imgData[p++] = pixels[i++];
	            alphaChannel[a++] = pixels[i++];
	          }
	          done = 0;
	          zlib.deflate(imgData, function(err, imgData) {
	            _this.imgData = imgData;
	            if (err) {
	              throw err;
	            }
	            if (++done === 2) {
	              return _this.finalize();
	            }
	          });
	          return zlib.deflate(alphaChannel, function(err, alphaChannel) {
	            _this.alphaChannel = alphaChannel;
	            if (err) {
	              throw err;
	            }
	            if (++done === 2) {
	              return _this.finalize();
	            }
	          });
	        };
	      })(this));
	    };

	    PNGImage.prototype.loadIndexedAlphaChannel = function(fn) {
	      var transparency;
	      transparency = this.image.transparency.indexed;
	      return this.image.decodePixels((function(_this) {
	        return function(pixels) {
	          var alphaChannel, i, j, _i, _ref;
	          alphaChannel = new Buffer(_this.width * _this.height);
	          i = 0;
	          for (j = _i = 0, _ref = pixels.length; _i < _ref; j = _i += 1) {
	            alphaChannel[i++] = transparency[pixels[j]];
	          }
	          return zlib.deflate(alphaChannel, function(err, alphaChannel) {
	            _this.alphaChannel = alphaChannel;
	            if (err) {
	              throw err;
	            }
	            return _this.finalize();
	          });
	        };
	      })(this));
	    };

	    return PNGImage;

	  })();

	  module.exports = PNGImage;

	}).call(this);

	/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(4).Buffer))

/***/ },
/* 37 */
/***/ function(module, exports, __webpack_require__) {

	/* jslint node: true */
	'use strict';

	var Line = __webpack_require__(24);
	var pack = __webpack_require__(25).pack;
	var offsetVector = __webpack_require__(25).offsetVector;
	var DocumentContext = __webpack_require__(20);

	/**
	* Creates an instance of ElementWriter - a line/vector writer, which adds
	* elements to current page and sets their positions based on the context
	*/
	function ElementWriter(context, tracker) {
		this.context = context;
		this.contextStack = [];
		this.tracker = tracker;
	}

	function addPageItem(page, item, index) {
		if(index === null || index === undefined || index < 0 || index > page.items.length) {
			page.items.push(item);
		} else {
			page.items.splice(index, 0, item);
		}
	}

	ElementWriter.prototype.addLine = function(line, dontUpdateContextPosition, index) {
		var height = line.getHeight();
		var context = this.context;
		var page = context.getCurrentPage(),
	      position = this.getCurrentPositionOnPage();

		if (context.availableHeight < height || !page) {
			return false;
		}

		line.x = context.x + (line.x || 0);
		line.y = context.y + (line.y || 0);

		this.alignLine(line);

	    addPageItem(page, {
	        type: 'line',
	        item: line
	    }, index);
		this.tracker.emit('lineAdded', line);

		if (!dontUpdateContextPosition) context.moveDown(height);

		return position;
	};

	ElementWriter.prototype.alignLine = function(line) {
		var width = this.context.availableWidth;
		var lineWidth = line.getWidth();

		var alignment = line.inlines && line.inlines.length > 0 && line.inlines[0].alignment;

		var offset = 0;
		switch(alignment) {
			case 'right':
				offset = width - lineWidth;
				break;
			case 'center':
				offset = (width - lineWidth) / 2;
				break;
		}

		if (offset) {
			line.x = (line.x || 0) + offset;
		}

		if (alignment === 'justify' &&
			!line.newLineForced &&
			!line.lastLineInParagraph &&
			line.inlines.length > 1) {
			var additionalSpacing = (width - lineWidth) / (line.inlines.length - 1);

			for(var i = 1, l = line.inlines.length; i < l; i++) {
				offset = i * additionalSpacing;

				line.inlines[i].x += offset;
			}
		}
	};

	ElementWriter.prototype.addImage = function(image, index) {
		var context = this.context;
		var page = context.getCurrentPage(),
	      position = this.getCurrentPositionOnPage();

		if (context.availableHeight < image._height || !page) {
			return false;
		}

		image.x = context.x + (image.x || 0);
		image.y = context.y;

		this.alignImage(image);

		addPageItem(page, {
	        type: 'image',
	        item: image
	    }, index);

		context.moveDown(image._height);

		return position;
	};

	ElementWriter.prototype.addQr = function(qr, index) {
		var context = this.context;
		var page = context.getCurrentPage(),
	      position = this.getCurrentPositionOnPage();

		if (context.availableHeight < qr._height || !page) {
			return false;
		}

		qr.x = context.x + (qr.x || 0);
		qr.y = context.y;

		this.alignImage(qr);

		for (var i=0, l=qr._canvas.length; i < l; i++) {
			var vector = qr._canvas[i];
			vector.x += qr.x;
			vector.y += qr.y;
			this.addVector(vector, true, true, index);
		}

		context.moveDown(qr._height);

		return position;
	};

	ElementWriter.prototype.alignImage = function(image) {
		var width = this.context.availableWidth;
		var imageWidth = image._minWidth;
		var offset = 0;
		switch(image._alignment) {
			case 'right':
				offset = width - imageWidth;
				break;
			case 'center':
				offset = (width - imageWidth) / 2;
				break;
		}

		if (offset) {
			image.x = (image.x || 0) + offset;
		}
	};

	ElementWriter.prototype.addVector = function(vector, ignoreContextX, ignoreContextY, index) {
		var context = this.context;
		var page = context.getCurrentPage(),
	      position = this.getCurrentPositionOnPage();

		if (page) {
			offsetVector(vector, ignoreContextX ? 0 : context.x, ignoreContextY ? 0 : context.y);
	        addPageItem(page, {
	            type: 'vector',
	            item: vector
	        }, index);
			return position;
		}
	};

	function cloneLine(line) {
		var result = new Line(line.maxWidth);

		for(var key in line) {
			if (line.hasOwnProperty(key)) {
				result[key] = line[key];
			}
		}

		return result;
	}

	ElementWriter.prototype.addFragment = function(block, useBlockXOffset, useBlockYOffset, dontUpdateContextPosition) {
		var ctx = this.context;
		var page = ctx.getCurrentPage();

		if (!useBlockXOffset && block.height > ctx.availableHeight) return false;

		block.items.forEach(function(item) {
	        switch(item.type) {
	            case 'line':
	                var l = cloneLine(item.item);

	                l.x = (l.x || 0) + (useBlockXOffset ? (block.xOffset || 0) : ctx.x);
	                l.y = (l.y || 0) + (useBlockYOffset ? (block.yOffset || 0) : ctx.y);

	                page.items.push({
	                    type: 'line',
	                    item: l
	                });
	                break;

	            case 'vector':
	                var v = pack(item.item);

	                offsetVector(v, useBlockXOffset ? (block.xOffset || 0) : ctx.x, useBlockYOffset ? (block.yOffset || 0) : ctx.y);
	                page.items.push({
	                    type: 'vector',
	                    item: v
	                });
	                break;

	            case 'image':
	                var img = pack(item.item);

	                img.x = (img.x || 0) + (useBlockXOffset ? (block.xOffset || 0) : ctx.x);
	                img.y = (img.y || 0) + (useBlockYOffset ? (block.yOffset || 0) : ctx.y);

	                page.items.push({
	                    type: 'image',
	                    item: img
	                });
	                break;
	        }
		});

		if (!dontUpdateContextPosition) ctx.moveDown(block.height);

		return true;
	};

	/**
	* Pushes the provided context onto the stack or creates a new one
	*
	* pushContext(context) - pushes the provided context and makes it current
	* pushContext(width, height) - creates and pushes a new context with the specified width and height
	* pushContext() - creates a new context for unbreakable blocks (with current availableWidth and full-page-height)
	*/
	ElementWriter.prototype.pushContext = function(contextOrWidth, height) {
		if (contextOrWidth === undefined) {
			height = this.context.getCurrentPage().height - this.context.pageMargins.top - this.context.pageMargins.bottom;
			contextOrWidth = this.context.availableWidth;
		}

		if (typeof contextOrWidth === 'number' || contextOrWidth instanceof Number) {
			contextOrWidth = new DocumentContext({ width: contextOrWidth, height: height }, { left: 0, right: 0, top: 0, bottom: 0 });
		}

		this.contextStack.push(this.context);
		this.context = contextOrWidth;
	};

	ElementWriter.prototype.popContext = function() {
		this.context = this.contextStack.pop();
	};

	ElementWriter.prototype.getCurrentPositionOnPage = function(){
		return (this.contextStack[0] || this.context).getCurrentPosition();
	};


	module.exports = ElementWriter;


/***/ },
/* 38 */
/***/ function(module, exports, __webpack_require__) {

	// Generated by CoffeeScript 1.7.1

	/*
	PDFPage - represents a single page in the PDF document
	By Devon Govett
	 */

	(function() {
	  var PDFPage;

	  PDFPage = (function() {
	    var DEFAULT_MARGINS, SIZES;

	    function PDFPage(document, options) {
	      var dimensions;
	      this.document = document;
	      if (options == null) {
	        options = {};
	      }
	      this.size = options.size || 'letter';
	      this.layout = options.layout || 'portrait';
	      if (typeof options.margin === 'number') {
	        this.margins = {
	          top: options.margin,
	          left: options.margin,
	          bottom: options.margin,
	          right: options.margin
	        };
	      } else {
	        this.margins = options.margins || DEFAULT_MARGINS;
	      }
	      dimensions = Array.isArray(this.size) ? this.size : SIZES[this.size.toUpperCase()];
	      this.width = dimensions[this.layout === 'portrait' ? 0 : 1];
	      this.height = dimensions[this.layout === 'portrait' ? 1 : 0];
	      this.content = this.document.ref();
	      this.resources = this.document.ref({
	        ProcSet: ['PDF', 'Text', 'ImageB', 'ImageC', 'ImageI']
	      });
	      Object.defineProperties(this, {
	        fonts: {
	          get: (function(_this) {
	            return function() {
	              var _base;
	              return (_base = _this.resources.data).Font != null ? _base.Font : _base.Font = {};
	            };
	          })(this)
	        },
	        xobjects: {
	          get: (function(_this) {
	            return function() {
	              var _base;
	              return (_base = _this.resources.data).XObject != null ? _base.XObject : _base.XObject = {};
	            };
	          })(this)
	        },
	        ext_gstates: {
	          get: (function(_this) {
	            return function() {
	              var _base;
	              return (_base = _this.resources.data).ExtGState != null ? _base.ExtGState : _base.ExtGState = {};
	            };
	          })(this)
	        },
	        patterns: {
	          get: (function(_this) {
	            return function() {
	              var _base;
	              return (_base = _this.resources.data).Pattern != null ? _base.Pattern : _base.Pattern = {};
	            };
	          })(this)
	        },
	        annotations: {
	          get: (function(_this) {
	            return function() {
	              var _base;
	              return (_base = _this.dictionary.data).Annots != null ? _base.Annots : _base.Annots = [];
	            };
	          })(this)
	        }
	      });
	      this.dictionary = this.document.ref({
	        Type: 'Page',
	        Parent: this.document._root.data.Pages,
	        MediaBox: [0, 0, this.width, this.height],
	        Contents: this.content,
	        Resources: this.resources
	      });
	    }

	    PDFPage.prototype.maxY = function() {
	      return this.height - this.margins.bottom;
	    };

	    PDFPage.prototype.write = function(chunk) {
	      return this.content.write(chunk);
	    };

	    PDFPage.prototype.end = function() {
	      this.dictionary.end();
	      this.resources.end();
	      return this.content.end();
	    };

	    DEFAULT_MARGINS = {
	      top: 72,
	      left: 72,
	      bottom: 72,
	      right: 72
	    };

	    SIZES = {
	      '4A0': [4767.87, 6740.79],
	      '2A0': [3370.39, 4767.87],
	      A0: [2383.94, 3370.39],
	      A1: [1683.78, 2383.94],
	      A2: [1190.55, 1683.78],
	      A3: [841.89, 1190.55],
	      A4: [595.28, 841.89],
	      A5: [419.53, 595.28],
	      A6: [297.64, 419.53],
	      A7: [209.76, 297.64],
	      A8: [147.40, 209.76],
	      A9: [104.88, 147.40],
	      A10: [73.70, 104.88],
	      B0: [2834.65, 4008.19],
	      B1: [2004.09, 2834.65],
	      B2: [1417.32, 2004.09],
	      B3: [1000.63, 1417.32],
	      B4: [708.66, 1000.63],
	      B5: [498.90, 708.66],
	      B6: [354.33, 498.90],
	      B7: [249.45, 354.33],
	      B8: [175.75, 249.45],
	      B9: [124.72, 175.75],
	      B10: [87.87, 124.72],
	      C0: [2599.37, 3676.54],
	      C1: [1836.85, 2599.37],
	      C2: [1298.27, 1836.85],
	      C3: [918.43, 1298.27],
	      C4: [649.13, 918.43],
	      C5: [459.21, 649.13],
	      C6: [323.15, 459.21],
	      C7: [229.61, 323.15],
	      C8: [161.57, 229.61],
	      C9: [113.39, 161.57],
	      C10: [79.37, 113.39],
	      RA0: [2437.80, 3458.27],
	      RA1: [1729.13, 2437.80],
	      RA2: [1218.90, 1729.13],
	      RA3: [864.57, 1218.90],
	      RA4: [609.45, 864.57],
	      SRA0: [2551.18, 3628.35],
	      SRA1: [1814.17, 2551.18],
	      SRA2: [1275.59, 1814.17],
	      SRA3: [907.09, 1275.59],
	      SRA4: [637.80, 907.09],
	      EXECUTIVE: [521.86, 756.00],
	      FOLIO: [612.00, 936.00],
	      LEGAL: [612.00, 1008.00],
	      LETTER: [612.00, 792.00],
	      TABLOID: [792.00, 1224.00]
	    };

	    return PDFPage;

	  })();

	  module.exports = PDFPage;

	}).call(this);


/***/ },
/* 39 */
/***/ function(module, exports, __webpack_require__) {

	// Generated by CoffeeScript 1.7.1
	(function() {
	  var KAPPA, SVGPath,
	    __slice = [].slice;

	  SVGPath = __webpack_require__(47);

	  KAPPA = 4.0 * ((Math.sqrt(2) - 1.0) / 3.0);

	  module.exports = {
	    initVector: function() {
	      this._ctm = [1, 0, 0, 1, 0, 0];
	      return this._ctmStack = [];
	    },
	    save: function() {
	      this._ctmStack.push(this._ctm.slice());
	      return this.addContent('q');
	    },
	    restore: function() {
	      this._ctm = this._ctmStack.pop() || [1, 0, 0, 1, 0, 0];
	      return this.addContent('Q');
	    },
	    closePath: function() {
	      return this.addContent('h');
	    },
	    lineWidth: function(w) {
	      return this.addContent("" + w + " w");
	    },
	    _CAP_STYLES: {
	      BUTT: 0,
	      ROUND: 1,
	      SQUARE: 2
	    },
	    lineCap: function(c) {
	      if (typeof c === 'string') {
	        c = this._CAP_STYLES[c.toUpperCase()];
	      }
	      return this.addContent("" + c + " J");
	    },
	    _JOIN_STYLES: {
	      MITER: 0,
	      ROUND: 1,
	      BEVEL: 2
	    },
	    lineJoin: function(j) {
	      if (typeof j === 'string') {
	        j = this._JOIN_STYLES[j.toUpperCase()];
	      }
	      return this.addContent("" + j + " j");
	    },
	    miterLimit: function(m) {
	      return this.addContent("" + m + " M");
	    },
	    dash: function(length, options) {
	      var phase, space, _ref;
	      if (options == null) {
	        options = {};
	      }
	      if (length == null) {
	        return this;
	      }
	      space = (_ref = options.space) != null ? _ref : length;
	      phase = options.phase || 0;
	      return this.addContent("[" + length + " " + space + "] " + phase + " d");
	    },
	    undash: function() {
	      return this.addContent("[] 0 d");
	    },
	    moveTo: function(x, y) {
	      return this.addContent("" + x + " " + y + " m");
	    },
	    lineTo: function(x, y) {
	      return this.addContent("" + x + " " + y + " l");
	    },
	    bezierCurveTo: function(cp1x, cp1y, cp2x, cp2y, x, y) {
	      return this.addContent("" + cp1x + " " + cp1y + " " + cp2x + " " + cp2y + " " + x + " " + y + " c");
	    },
	    quadraticCurveTo: function(cpx, cpy, x, y) {
	      return this.addContent("" + cpx + " " + cpy + " " + x + " " + y + " v");
	    },
	    rect: function(x, y, w, h) {
	      return this.addContent("" + x + " " + y + " " + w + " " + h + " re");
	    },
	    roundedRect: function(x, y, w, h, r) {
	      if (r == null) {
	        r = 0;
	      }
	      this.moveTo(x + r, y);
	      this.lineTo(x + w - r, y);
	      this.quadraticCurveTo(x + w, y, x + w, y + r);
	      this.lineTo(x + w, y + h - r);
	      this.quadraticCurveTo(x + w, y + h, x + w - r, y + h);
	      this.lineTo(x + r, y + h);
	      this.quadraticCurveTo(x, y + h, x, y + h - r);
	      this.lineTo(x, y + r);
	      return this.quadraticCurveTo(x, y, x + r, y);
	    },
	    ellipse: function(x, y, r1, r2) {
	      var ox, oy, xe, xm, ye, ym;
	      if (r2 == null) {
	        r2 = r1;
	      }
	      x -= r1;
	      y -= r2;
	      ox = r1 * KAPPA;
	      oy = r2 * KAPPA;
	      xe = x + r1 * 2;
	      ye = y + r2 * 2;
	      xm = x + r1;
	      ym = y + r2;
	      this.moveTo(x, ym);
	      this.bezierCurveTo(x, ym - oy, xm - ox, y, xm, y);
	      this.bezierCurveTo(xm + ox, y, xe, ym - oy, xe, ym);
	      this.bezierCurveTo(xe, ym + oy, xm + ox, ye, xm, ye);
	      this.bezierCurveTo(xm - ox, ye, x, ym + oy, x, ym);
	      return this.closePath();
	    },
	    circle: function(x, y, radius) {
	      return this.ellipse(x, y, radius);
	    },
	    polygon: function() {
	      var point, points, _i, _len;
	      points = 1 <= arguments.length ? __slice.call(arguments, 0) : [];
	      this.moveTo.apply(this, points.shift());
	      for (_i = 0, _len = points.length; _i < _len; _i++) {
	        point = points[_i];
	        this.lineTo.apply(this, point);
	      }
	      return this.closePath();
	    },
	    path: function(path) {
	      SVGPath.apply(this, path);
	      return this;
	    },
	    _windingRule: function(rule) {
	      if (/even-?odd/.test(rule)) {
	        return '*';
	      }
	      return '';
	    },
	    fill: function(color, rule) {
	      if (/(even-?odd)|(non-?zero)/.test(color)) {
	        rule = color;
	        color = null;
	      }
	      if (color) {
	        this.fillColor(color);
	      }
	      return this.addContent('f' + this._windingRule(rule));
	    },
	    stroke: function(color) {
	      if (color) {
	        this.strokeColor(color);
	      }
	      return this.addContent('S');
	    },
	    fillAndStroke: function(fillColor, strokeColor, rule) {
	      var isFillRule;
	      if (strokeColor == null) {
	        strokeColor = fillColor;
	      }
	      isFillRule = /(even-?odd)|(non-?zero)/;
	      if (isFillRule.test(fillColor)) {
	        rule = fillColor;
	        fillColor = null;
	      }
	      if (isFillRule.test(strokeColor)) {
	        rule = strokeColor;
	        strokeColor = fillColor;
	      }
	      if (fillColor) {
	        this.fillColor(fillColor);
	        this.strokeColor(strokeColor);
	      }
	      return this.addContent('B' + this._windingRule(rule));
	    },
	    clip: function(rule) {
	      return this.addContent('W' + this._windingRule(rule) + ' n');
	    },
	    transform: function(m11, m12, m21, m22, dx, dy) {
	      var m, m0, m1, m2, m3, m4, m5, v, values;
	      m = this._ctm;
	      m0 = m[0], m1 = m[1], m2 = m[2], m3 = m[3], m4 = m[4], m5 = m[5];
	      m[0] = m0 * m11 + m2 * m12;
	      m[1] = m1 * m11 + m3 * m12;
	      m[2] = m0 * m21 + m2 * m22;
	      m[3] = m1 * m21 + m3 * m22;
	      m[4] = m0 * dx + m2 * dy + m4;
	      m[5] = m1 * dx + m3 * dy + m5;
	      values = ((function() {
	        var _i, _len, _ref, _results;
	        _ref = [m11, m12, m21, m22, dx, dy];
	        _results = [];
	        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
	          v = _ref[_i];
	          _results.push(+v.toFixed(5));
	        }
	        return _results;
	      })()).join(' ');
	      return this.addContent("" + values + " cm");
	    },
	    translate: function(x, y) {
	      return this.transform(1, 0, 0, 1, x, y);
	    },
	    rotate: function(angle, options) {
	      var cos, rad, sin, x, x1, y, y1, _ref;
	      if (options == null) {
	        options = {};
	      }
	      rad = angle * Math.PI / 180;
	      cos = Math.cos(rad);
	      sin = Math.sin(rad);
	      x = y = 0;
	      if (options.origin != null) {
	        _ref = options.origin, x = _ref[0], y = _ref[1];
	        x1 = x * cos - y * sin;
	        y1 = x * sin + y * cos;
	        x -= x1;
	        y -= y1;
	      }
	      return this.transform(cos, sin, -sin, cos, x, y);
	    },
	    scale: function(xFactor, yFactor, options) {
	      var x, y, _ref;
	      if (yFactor == null) {
	        yFactor = xFactor;
	      }
	      if (options == null) {
	        options = {};
	      }
	      if (arguments.length === 2) {
	        yFactor = xFactor;
	        options = yFactor;
	      }
	      x = y = 0;
	      if (options.origin != null) {
	        _ref = options.origin, x = _ref[0], y = _ref[1];
	        x -= xFactor * x;
	        y -= yFactor * y;
	      }
	      return this.transform(xFactor, 0, 0, yFactor, x, y);
	    }
	  };

	}).call(this);


/***/ },
/* 40 */
/***/ function(module, exports, __webpack_require__) {

	// Generated by CoffeeScript 1.7.1
	(function() {
	  var LineWrapper;

	  LineWrapper = __webpack_require__(48);

	  module.exports = {
	    initText: function() {
	      this.x = 0;
	      this.y = 0;
	      return this._lineGap = 0;
	    },
	    lineGap: function(_lineGap) {
	      this._lineGap = _lineGap;
	      return this;
	    },
	    moveDown: function(lines) {
	      if (lines == null) {
	        lines = 1;
	      }
	      this.y += this.currentLineHeight(true) * lines + this._lineGap;
	      return this;
	    },
	    moveUp: function(lines) {
	      if (lines == null) {
	        lines = 1;
	      }
	      this.y -= this.currentLineHeight(true) * lines + this._lineGap;
	      return this;
	    },
	    _text: function(text, x, y, options, lineCallback) {
	      var line, wrapper, _i, _len, _ref;
	      options = this._initOptions(x, y, options);
	      text = '' + text;
	      if (options.wordSpacing) {
	        text = text.replace(/\s{2,}/g, ' ');
	      }
	      if (options.width) {
	        wrapper = this._wrapper;
	        if (!wrapper) {
	          wrapper = new LineWrapper(this, options);
	          wrapper.on('line', lineCallback);
	        }
	        this._wrapper = options.continued ? wrapper : null;
	        this._textOptions = options.continued ? options : null;
	        wrapper.wrap(text, options);
	      } else {
	        _ref = text.split('\n');
	        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
	          line = _ref[_i];
	          lineCallback(line, options);
	        }
	      }
	      return this;
	    },
	    text: function(text, x, y, options) {
	      return this._text(text, x, y, options, this._line.bind(this));
	    },
	    widthOfString: function(string, options) {
	      if (options == null) {
	        options = {};
	      }
	      return this._font.widthOfString(string, this._fontSize) + (options.characterSpacing || 0) * (string.length - 1);
	    },
	    heightOfString: function(text, options) {
	      var height, lineGap, x, y;
	      if (options == null) {
	        options = {};
	      }
	      x = this.x, y = this.y;
	      options = this._initOptions(options);
	      options.height = Infinity;
	      lineGap = options.lineGap || this._lineGap || 0;
	      this._text(text, this.x, this.y, options, (function(_this) {
	        return function(line, options) {
	          return _this.y += _this.currentLineHeight(true) + lineGap;
	        };
	      })(this));
	      height = this.y - y;
	      this.x = x;
	      this.y = y;
	      return height;
	    },
	    list: function(list, x, y, options, wrapper) {
	      var flatten, i, indent, itemIndent, items, level, levels, r;
	      options = this._initOptions(x, y, options);
	      r = Math.round((this._font.ascender / 1000 * this._fontSize) / 3);
	      indent = options.textIndent || r * 5;
	      itemIndent = options.bulletIndent || r * 8;
	      level = 1;
	      items = [];
	      levels = [];
	      flatten = function(list) {
	        var i, item, _i, _len, _results;
	        _results = [];
	        for (i = _i = 0, _len = list.length; _i < _len; i = ++_i) {
	          item = list[i];
	          if (Array.isArray(item)) {
	            level++;
	            flatten(item);
	            _results.push(level--);
	          } else {
	            items.push(item);
	            _results.push(levels.push(level));
	          }
	        }
	        return _results;
	      };
	      flatten(list);
	      wrapper = new LineWrapper(this, options);
	      wrapper.on('line', this._line.bind(this));
	      level = 1;
	      i = 0;
	      wrapper.on('firstLine', (function(_this) {
	        return function() {
	          var diff, l;
	          if ((l = levels[i++]) !== level) {
	            diff = itemIndent * (l - level);
	            _this.x += diff;
	            wrapper.lineWidth -= diff;
	            level = l;
	          }
	          _this.circle(_this.x - indent + r, _this.y + r + (r / 2), r);
	          return _this.fill();
	        };
	      })(this));
	      wrapper.on('sectionStart', (function(_this) {
	        return function() {
	          var pos;
	          pos = indent + itemIndent * (level - 1);
	          _this.x += pos;
	          return wrapper.lineWidth -= pos;
	        };
	      })(this));
	      wrapper.on('sectionEnd', (function(_this) {
	        return function() {
	          var pos;
	          pos = indent + itemIndent * (level - 1);
	          _this.x -= pos;
	          return wrapper.lineWidth += pos;
	        };
	      })(this));
	      wrapper.wrap(items.join('\n'), options);
	      return this;
	    },
	    _initOptions: function(x, y, options) {
	      var key, margins, val, _ref;
	      if (x == null) {
	        x = {};
	      }
	      if (options == null) {
	        options = {};
	      }
	      if (typeof x === 'object') {
	        options = x;
	        x = null;
	      }
	      options = (function() {
	        var k, opts, v;
	        opts = {};
	        for (k in options) {
	          v = options[k];
	          opts[k] = v;
	        }
	        return opts;
	      })();
	      if (this._textOptions) {
	        _ref = this._textOptions;
	        for (key in _ref) {
	          val = _ref[key];
	          if (key !== 'continued') {
	            if (options[key] == null) {
	              options[key] = val;
	            }
	          }
	        }
	      }
	      if (x != null) {
	        this.x = x;
	      }
	      if (y != null) {
	        this.y = y;
	      }
	      if (options.lineBreak !== false) {
	        margins = this.page.margins;
	        if (options.width == null) {
	          options.width = this.page.width - this.x - margins.right;
	        }
	      }
	      options.columns || (options.columns = 0);
	      if (options.columnGap == null) {
	        options.columnGap = 18;
	      }
	      return options;
	    },
	    _line: function(text, options, wrapper) {
	      var lineGap;
	      if (options == null) {
	        options = {};
	      }
	      this._fragment(text, this.x, this.y, options);
	      lineGap = options.lineGap || this._lineGap || 0;
	      if (!wrapper) {
	        return this.x += this.widthOfString(text);
	      } else {
	        return this.y += this.currentLineHeight(true) + lineGap;
	      }
	    },
	    _fragment: function(text, x, y, options) {
	      var align, characterSpacing, commands, d, encoded, i, lineWidth, lineY, mode, renderedWidth, spaceWidth, textWidth, word, wordSpacing, words, _base, _i, _len, _name;
	      text = '' + text;
	      if (text.length === 0) {
	        return;
	      }
	      align = options.align || 'left';
	      wordSpacing = options.wordSpacing || 0;
	      characterSpacing = options.characterSpacing || 0;
	      if (options.width) {
	        switch (align) {
	          case 'right':
	            textWidth = this.widthOfString(text.replace(/\s+$/, ''), options);
	            x += options.lineWidth - textWidth;
	            break;
	          case 'center':
	            x += options.lineWidth / 2 - options.textWidth / 2;
	            break;
	          case 'justify':
	            words = text.trim().split(/\s+/);
	            textWidth = this.widthOfString(text.replace(/\s+/g, ''), options);
	            spaceWidth = this.widthOfString(' ') + characterSpacing;
	            wordSpacing = Math.max(0, (options.lineWidth - textWidth) / Math.max(1, words.length - 1) - spaceWidth);
	        }
	      }
	      renderedWidth = options.textWidth + (wordSpacing * (options.wordCount - 1)) + (characterSpacing * (text.length - 1));
	      if (options.link) {
	        this.link(x, y, renderedWidth, this.currentLineHeight(), options.link);
	      }
	      if (options.underline || options.strike) {
	        this.save();
	        if (!options.stroke) {
	          this.strokeColor.apply(this, this._fillColor);
	        }
	        lineWidth = this._fontSize < 10 ? 0.5 : Math.floor(this._fontSize / 10);
	        this.lineWidth(lineWidth);
	        d = options.underline ? 1 : 2;
	        lineY = y + this.currentLineHeight() / d;
	        if (options.underline) {
	          lineY -= lineWidth;
	        }
	        this.moveTo(x, lineY);
	        this.lineTo(x + renderedWidth, lineY);
	        this.stroke();
	        this.restore();
	      }
	      this.save();
	      this.transform(1, 0, 0, -1, 0, this.page.height);
	      y = this.page.height - y - (this._font.ascender / 1000 * this._fontSize);
	      if ((_base = this.page.fonts)[_name = this._font.id] == null) {
	        _base[_name] = this._font.ref();
	      }
	      this._font.use(text);
	      this.addContent("BT");
	      this.addContent("" + x + " " + y + " Td");
	      this.addContent("/" + this._font.id + " " + this._fontSize + " Tf");
	      mode = options.fill && options.stroke ? 2 : options.stroke ? 1 : 0;
	      if (mode) {
	        this.addContent("" + mode + " Tr");
	      }
	      if (characterSpacing) {
	        this.addContent("" + characterSpacing + " Tc");
	      }
	      if (wordSpacing) {
	        words = text.trim().split(/\s+/);
	        wordSpacing += this.widthOfString(' ') + characterSpacing;
	        wordSpacing *= 1000 / this._fontSize;
	        commands = [];
	        for (_i = 0, _len = words.length; _i < _len; _i++) {
	          word = words[_i];
	          encoded = this._font.encode(word);
	          encoded = ((function() {
	            var _j, _ref, _results;
	            _results = [];
	            for (i = _j = 0, _ref = encoded.length; _j < _ref; i = _j += 1) {
	              _results.push(encoded.charCodeAt(i).toString(16));
	            }
	            return _results;
	          })()).join('');
	          commands.push("<" + encoded + "> " + (-wordSpacing));
	        }
	        this.addContent("[" + (commands.join(' ')) + "] TJ");
	      } else {
	        encoded = this._font.encode(text);
	        encoded = ((function() {
	          var _j, _ref, _results;
	          _results = [];
	          for (i = _j = 0, _ref = encoded.length; _j < _ref; i = _j += 1) {
	            _results.push(encoded.charCodeAt(i).toString(16));
	          }
	          return _results;
	        })()).join('');
	        this.addContent("<" + encoded + "> Tj");
	      }
	      this.addContent("ET");
	      return this.restore();
	    }
	  };

	}).call(this);


/***/ },
/* 41 */
/***/ function(module, exports, __webpack_require__) {

	// Generated by CoffeeScript 1.7.1
	(function() {
	  var PDFGradient, PDFLinearGradient, PDFRadialGradient, namedColors, _ref;

	  _ref = __webpack_require__(49), PDFGradient = _ref.PDFGradient, PDFLinearGradient = _ref.PDFLinearGradient, PDFRadialGradient = _ref.PDFRadialGradient;

	  module.exports = {
	    initColor: function() {
	      this._opacityRegistry = {};
	      this._opacityCount = 0;
	      return this._gradCount = 0;
	    },
	    _normalizeColor: function(color) {
	      var hex, part;
	      if (color instanceof PDFGradient) {
	        return color;
	      }
	      if (typeof color === 'string') {
	        if (color.charAt(0) === '#') {
	          if (color.length === 4) {
	            color = color.replace(/#([0-9A-F])([0-9A-F])([0-9A-F])/i, "#$1$1$2$2$3$3");
	          }
	          hex = parseInt(color.slice(1), 16);
	          color = [hex >> 16, hex >> 8 & 0xff, hex & 0xff];
	        } else if (namedColors[color]) {
	          color = namedColors[color];
	        }
	      }
	      if (Array.isArray(color)) {
	        if (color.length === 3) {
	          color = (function() {
	            var _i, _len, _results;
	            _results = [];
	            for (_i = 0, _len = color.length; _i < _len; _i++) {
	              part = color[_i];
	              _results.push(part / 255);
	            }
	            return _results;
	          })();
	        } else if (color.length === 4) {
	          color = (function() {
	            var _i, _len, _results;
	            _results = [];
	            for (_i = 0, _len = color.length; _i < _len; _i++) {
	              part = color[_i];
	              _results.push(part / 100);
	            }
	            return _results;
	          })();
	        }
	        return color;
	      }
	      return null;
	    },
	    _setColor: function(color, stroke) {
	      var gstate, name, op, space;
	      color = this._normalizeColor(color);
	      if (!color) {
	        return false;
	      }
	      if (this._sMasked) {
	        gstate = this.ref({
	          Type: 'ExtGState',
	          SMask: 'None'
	        });
	        gstate.end();
	        name = "Gs" + (++this._opacityCount);
	        this.page.ext_gstates[name] = gstate;
	        this.addContent("/" + name + " gs");
	        this._sMasked = false;
	      }
	      op = stroke ? 'SCN' : 'scn';
	      if (color instanceof PDFGradient) {
	        this._setColorSpace('Pattern', stroke);
	        color.apply(op);
	      } else {
	        space = color.length === 4 ? 'DeviceCMYK' : 'DeviceRGB';
	        this._setColorSpace(space, stroke);
	        color = color.join(' ');
	        this.addContent("" + color + " " + op);
	      }
	      return true;
	    },
	    _setColorSpace: function(space, stroke) {
	      var op;
	      op = stroke ? 'CS' : 'cs';
	      return this.addContent("/" + space + " " + op);
	    },
	    fillColor: function(color, opacity) {
	      var set;
	      if (opacity == null) {
	        opacity = 1;
	      }
	      set = this._setColor(color, false);
	      if (set) {
	        this.fillOpacity(opacity);
	      }
	      this._fillColor = [color, opacity];
	      return this;
	    },
	    strokeColor: function(color, opacity) {
	      var set;
	      if (opacity == null) {
	        opacity = 1;
	      }
	      set = this._setColor(color, true);
	      if (set) {
	        this.strokeOpacity(opacity);
	      }
	      return this;
	    },
	    opacity: function(opacity) {
	      this._doOpacity(opacity, opacity);
	      return this;
	    },
	    fillOpacity: function(opacity) {
	      this._doOpacity(opacity, null);
	      return this;
	    },
	    strokeOpacity: function(opacity) {
	      this._doOpacity(null, opacity);
	      return this;
	    },
	    _doOpacity: function(fillOpacity, strokeOpacity) {
	      var dictionary, id, key, name, _ref1;
	      if (!((fillOpacity != null) || (strokeOpacity != null))) {
	        return;
	      }
	      if (fillOpacity != null) {
	        fillOpacity = Math.max(0, Math.min(1, fillOpacity));
	      }
	      if (strokeOpacity != null) {
	        strokeOpacity = Math.max(0, Math.min(1, strokeOpacity));
	      }
	      key = "" + fillOpacity + "_" + strokeOpacity;
	      if (this._opacityRegistry[key]) {
	        _ref1 = this._opacityRegistry[key], dictionary = _ref1[0], name = _ref1[1];
	      } else {
	        dictionary = {
	          Type: 'ExtGState'
	        };
	        if (fillOpacity != null) {
	          dictionary.ca = fillOpacity;
	        }
	        if (strokeOpacity != null) {
	          dictionary.CA = strokeOpacity;
	        }
	        dictionary = this.ref(dictionary);
	        dictionary.end();
	        id = ++this._opacityCount;
	        name = "Gs" + id;
	        this._opacityRegistry[key] = [dictionary, name];
	      }
	      this.page.ext_gstates[name] = dictionary;
	      return this.addContent("/" + name + " gs");
	    },
	    linearGradient: function(x1, y1, x2, y2) {
	      return new PDFLinearGradient(this, x1, y1, x2, y2);
	    },
	    radialGradient: function(x1, y1, r1, x2, y2, r2) {
	      return new PDFRadialGradient(this, x1, y1, r1, x2, y2, r2);
	    }
	  };

	  namedColors = {
	    aliceblue: [240, 248, 255],
	    antiquewhite: [250, 235, 215],
	    aqua: [0, 255, 255],
	    aquamarine: [127, 255, 212],
	    azure: [240, 255, 255],
	    beige: [245, 245, 220],
	    bisque: [255, 228, 196],
	    black: [0, 0, 0],
	    blanchedalmond: [255, 235, 205],
	    blue: [0, 0, 255],
	    blueviolet: [138, 43, 226],
	    brown: [165, 42, 42],
	    burlywood: [222, 184, 135],
	    cadetblue: [95, 158, 160],
	    chartreuse: [127, 255, 0],
	    chocolate: [210, 105, 30],
	    coral: [255, 127, 80],
	    cornflowerblue: [100, 149, 237],
	    cornsilk: [255, 248, 220],
	    crimson: [220, 20, 60],
	    cyan: [0, 255, 255],
	    darkblue: [0, 0, 139],
	    darkcyan: [0, 139, 139],
	    darkgoldenrod: [184, 134, 11],
	    darkgray: [169, 169, 169],
	    darkgreen: [0, 100, 0],
	    darkgrey: [169, 169, 169],
	    darkkhaki: [189, 183, 107],
	    darkmagenta: [139, 0, 139],
	    darkolivegreen: [85, 107, 47],
	    darkorange: [255, 140, 0],
	    darkorchid: [153, 50, 204],
	    darkred: [139, 0, 0],
	    darksalmon: [233, 150, 122],
	    darkseagreen: [143, 188, 143],
	    darkslateblue: [72, 61, 139],
	    darkslategray: [47, 79, 79],
	    darkslategrey: [47, 79, 79],
	    darkturquoise: [0, 206, 209],
	    darkviolet: [148, 0, 211],
	    deeppink: [255, 20, 147],
	    deepskyblue: [0, 191, 255],
	    dimgray: [105, 105, 105],
	    dimgrey: [105, 105, 105],
	    dodgerblue: [30, 144, 255],
	    firebrick: [178, 34, 34],
	    floralwhite: [255, 250, 240],
	    forestgreen: [34, 139, 34],
	    fuchsia: [255, 0, 255],
	    gainsboro: [220, 220, 220],
	    ghostwhite: [248, 248, 255],
	    gold: [255, 215, 0],
	    goldenrod: [218, 165, 32],
	    gray: [128, 128, 128],
	    grey: [128, 128, 128],
	    green: [0, 128, 0],
	    greenyellow: [173, 255, 47],
	    honeydew: [240, 255, 240],
	    hotpink: [255, 105, 180],
	    indianred: [205, 92, 92],
	    indigo: [75, 0, 130],
	    ivory: [255, 255, 240],
	    khaki: [240, 230, 140],
	    lavender: [230, 230, 250],
	    lavenderblush: [255, 240, 245],
	    lawngreen: [124, 252, 0],
	    lemonchiffon: [255, 250, 205],
	    lightblue: [173, 216, 230],
	    lightcoral: [240, 128, 128],
	    lightcyan: [224, 255, 255],
	    lightgoldenrodyellow: [250, 250, 210],
	    lightgray: [211, 211, 211],
	    lightgreen: [144, 238, 144],
	    lightgrey: [211, 211, 211],
	    lightpink: [255, 182, 193],
	    lightsalmon: [255, 160, 122],
	    lightseagreen: [32, 178, 170],
	    lightskyblue: [135, 206, 250],
	    lightslategray: [119, 136, 153],
	    lightslategrey: [119, 136, 153],
	    lightsteelblue: [176, 196, 222],
	    lightyellow: [255, 255, 224],
	    lime: [0, 255, 0],
	    limegreen: [50, 205, 50],
	    linen: [250, 240, 230],
	    magenta: [255, 0, 255],
	    maroon: [128, 0, 0],
	    mediumaquamarine: [102, 205, 170],
	    mediumblue: [0, 0, 205],
	    mediumorchid: [186, 85, 211],
	    mediumpurple: [147, 112, 219],
	    mediumseagreen: [60, 179, 113],
	    mediumslateblue: [123, 104, 238],
	    mediumspringgreen: [0, 250, 154],
	    mediumturquoise: [72, 209, 204],
	    mediumvioletred: [199, 21, 133],
	    midnightblue: [25, 25, 112],
	    mintcream: [245, 255, 250],
	    mistyrose: [255, 228, 225],
	    moccasin: [255, 228, 181],
	    navajowhite: [255, 222, 173],
	    navy: [0, 0, 128],
	    oldlace: [253, 245, 230],
	    olive: [128, 128, 0],
	    olivedrab: [107, 142, 35],
	    orange: [255, 165, 0],
	    orangered: [255, 69, 0],
	    orchid: [218, 112, 214],
	    palegoldenrod: [238, 232, 170],
	    palegreen: [152, 251, 152],
	    paleturquoise: [175, 238, 238],
	    palevioletred: [219, 112, 147],
	    papayawhip: [255, 239, 213],
	    peachpuff: [255, 218, 185],
	    peru: [205, 133, 63],
	    pink: [255, 192, 203],
	    plum: [221, 160, 221],
	    powderblue: [176, 224, 230],
	    purple: [128, 0, 128],
	    red: [255, 0, 0],
	    rosybrown: [188, 143, 143],
	    royalblue: [65, 105, 225],
	    saddlebrown: [139, 69, 19],
	    salmon: [250, 128, 114],
	    sandybrown: [244, 164, 96],
	    seagreen: [46, 139, 87],
	    seashell: [255, 245, 238],
	    sienna: [160, 82, 45],
	    silver: [192, 192, 192],
	    skyblue: [135, 206, 235],
	    slateblue: [106, 90, 205],
	    slategray: [112, 128, 144],
	    slategrey: [112, 128, 144],
	    snow: [255, 250, 250],
	    springgreen: [0, 255, 127],
	    steelblue: [70, 130, 180],
	    tan: [210, 180, 140],
	    teal: [0, 128, 128],
	    thistle: [216, 191, 216],
	    tomato: [255, 99, 71],
	    turquoise: [64, 224, 208],
	    violet: [238, 130, 238],
	    wheat: [245, 222, 179],
	    white: [255, 255, 255],
	    whitesmoke: [245, 245, 245],
	    yellow: [255, 255, 0],
	    yellowgreen: [154, 205, 50]
	  };

	}).call(this);


/***/ },
/* 42 */
/***/ function(module, exports, __webpack_require__) {

	/* WEBPACK VAR INJECTION */(function(Buffer) {// Generated by CoffeeScript 1.7.1
	(function() {
	  var PDFImage;

	  PDFImage = __webpack_require__(17);

	  module.exports = {
	    initImages: function() {
	      this._imageRegistry = {};
	      return this._imageCount = 0;
	    },
	    image: function(src, x, y, options) {
	      var bh, bp, bw, h, hp, image, ip, w, wp, _base, _name, _ref, _ref1, _ref2;
	      if (options == null) {
	        options = {};
	      }
	      if (typeof x === 'object') {
	        options = x;
	        x = null;
	      }
	      x = (_ref = x != null ? x : options.x) != null ? _ref : this.x;
	      y = (_ref1 = y != null ? y : options.y) != null ? _ref1 : this.y;
	      if (!Buffer.isBuffer(src)) {
	        image = this._imageRegistry[src];
	      }
	      if (!image) {
	        image = PDFImage.open(src, 'I' + (++this._imageCount));
	        image.embed(this);
	        if (!Buffer.isBuffer(src)) {
	          this._imageRegistry[src] = image;
	        }
	      }
	      if ((_base = this.page.xobjects)[_name = image.label] == null) {
	        _base[_name] = image.obj;
	      }
	      w = options.width || image.width;
	      h = options.height || image.height;
	      if (options.width && !options.height) {
	        wp = w / image.width;
	        w = image.width * wp;
	        h = image.height * wp;
	      } else if (options.height && !options.width) {
	        hp = h / image.height;
	        w = image.width * hp;
	        h = image.height * hp;
	      } else if (options.scale) {
	        w = image.width * options.scale;
	        h = image.height * options.scale;
	      } else if (options.fit) {
	        _ref2 = options.fit, bw = _ref2[0], bh = _ref2[1];
	        bp = bw / bh;
	        ip = image.width / image.height;
	        if (ip > bp) {
	          w = bw;
	          h = bw / ip;
	        } else {
	          h = bh;
	          w = bh * ip;
	        }
	        if (options.align === 'center') {
	          x = x + bw / 2 - w / 2;
	        } else if (options.align === 'right') {
	          x = x + bw - w;
	        }
	        if (options.valign === 'center') {
	          y = y + bh / 2 - h / 2;
	        } else if (options.valign === 'bottom') {
	          y = y + bh - h;
	        }
	      }
	      if (this.y === y) {
	        this.y += h;
	      }
	      this.save();
	      this.transform(w, 0, 0, -h, x, y + h);
	      this.addContent("/" + image.label + " Do");
	      this.restore();
	      return this;
	    }
	  };

	}).call(this);

	/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(4).Buffer))

/***/ },
/* 43 */
/***/ function(module, exports, __webpack_require__) {

	// Generated by CoffeeScript 1.7.1
	(function() {
	  module.exports = {
	    annotate: function(x, y, w, h, options) {
	      var key, ref, val;
	      options.Type = 'Annot';
	      options.Rect = this._convertRect(x, y, w, h);
	      options.Border = [0, 0, 0];
	      if (options.Subtype !== 'Link') {
	        if (options.C == null) {
	          options.C = this._normalizeColor(options.color || [0, 0, 0]);
	        }
	      }
	      delete options.color;
	      if (typeof options.Dest === 'string') {
	        options.Dest = new String(options.Dest);
	      }
	      for (key in options) {
	        val = options[key];
	        options[key[0].toUpperCase() + key.slice(1)] = val;
	      }
	      ref = this.ref(options);
	      this.page.annotations.push(ref);
	      ref.end();
	      return this;
	    },
	    note: function(x, y, w, h, contents, options) {
	      if (options == null) {
	        options = {};
	      }
	      options.Subtype = 'Text';
	      options.Contents = new String(contents);
	      options.Name = 'Comment';
	      if (options.color == null) {
	        options.color = [243, 223, 92];
	      }
	      return this.annotate(x, y, w, h, options);
	    },
	    link: function(x, y, w, h, url, options) {
	      if (options == null) {
	        options = {};
	      }
	      options.Subtype = 'Link';
	      options.A = this.ref({
	        S: 'URI',
	        URI: new String(url)
	      });
	      options.A.end();
	      return this.annotate(x, y, w, h, options);
	    },
	    _markup: function(x, y, w, h, options) {
	      var x1, x2, y1, y2, _ref;
	      if (options == null) {
	        options = {};
	      }
	      _ref = this._convertRect(x, y, w, h), x1 = _ref[0], y1 = _ref[1], x2 = _ref[2], y2 = _ref[3];
	      options.QuadPoints = [x1, y2, x2, y2, x1, y1, x2, y1];
	      options.Contents = new String;
	      return this.annotate(x, y, w, h, options);
	    },
	    highlight: function(x, y, w, h, options) {
	      if (options == null) {
	        options = {};
	      }
	      options.Subtype = 'Highlight';
	      if (options.color == null) {
	        options.color = [241, 238, 148];
	      }
	      return this._markup(x, y, w, h, options);
	    },
	    underline: function(x, y, w, h, options) {
	      if (options == null) {
	        options = {};
	      }
	      options.Subtype = 'Underline';
	      return this._markup(x, y, w, h, options);
	    },
	    strike: function(x, y, w, h, options) {
	      if (options == null) {
	        options = {};
	      }
	      options.Subtype = 'StrikeOut';
	      return this._markup(x, y, w, h, options);
	    },
	    lineAnnotation: function(x1, y1, x2, y2, options) {
	      if (options == null) {
	        options = {};
	      }
	      options.Subtype = 'Line';
	      options.Contents = new String;
	      options.L = [x1, this.page.height - y1, x2, this.page.height - y2];
	      return this.annotate(x1, y1, x2, y2, options);
	    },
	    rectAnnotation: function(x, y, w, h, options) {
	      if (options == null) {
	        options = {};
	      }
	      options.Subtype = 'Square';
	      options.Contents = new String;
	      return this.annotate(x, y, w, h, options);
	    },
	    ellipseAnnotation: function(x, y, w, h, options) {
	      if (options == null) {
	        options = {};
	      }
	      options.Subtype = 'Circle';
	      options.Contents = new String;
	      return this.annotate(x, y, w, h, options);
	    },
	    textAnnotation: function(x, y, w, h, text, options) {
	      if (options == null) {
	        options = {};
	      }
	      options.Subtype = 'FreeText';
	      options.Contents = new String(text);
	      options.DA = new String;
	      return this.annotate(x, y, w, h, options);
	    },
	    _convertRect: function(x1, y1, w, h) {
	      var m0, m1, m2, m3, m4, m5, x2, y2, _ref;
	      y2 = y1;
	      y1 += h;
	      x2 = x1 + w;
	      _ref = this._ctm, m0 = _ref[0], m1 = _ref[1], m2 = _ref[2], m3 = _ref[3], m4 = _ref[4], m5 = _ref[5];
	      x1 = m0 * x1 + m2 * y1 + m4;
	      y1 = m1 * x1 + m3 * y1 + m5;
	      x2 = m0 * x2 + m2 * y2 + m4;
	      y2 = m1 * x2 + m3 * y2 + m5;
	      return [x1, y1, x2, y2];
	    }
	  };

	}).call(this);


/***/ },
/* 44 */
/***/ function(module, exports, __webpack_require__) {

	// Generated by CoffeeScript 1.7.1
	(function() {
	  var PDFFont;

	  PDFFont = __webpack_require__(52);

	  module.exports = {
	    initFonts: function() {
	      this._fontFamilies = {};
	      this._fontCount = 0;
	      this._fontSize = 12;
	      this._font = null;
	      this._registeredFonts = {};
	      
	    },
	    font: function(src, family, size) {
	      var cacheKey, font, id, _ref;
	      if (typeof family === 'number') {
	        size = family;
	        family = null;
	      }
	      if (typeof src === 'string' && this._registeredFonts[src]) {
	        cacheKey = src;
	        _ref = this._registeredFonts[src], src = _ref.src, family = _ref.family;
	      } else {
	        cacheKey = family || src;
	        if (typeof cacheKey !== 'string') {
	          cacheKey = null;
	        }
	      }
	      if (size != null) {
	        this.fontSize(size);
	      }
	      if (font = this._fontFamilies[cacheKey]) {
	        this._font = font;
	        return this;
	      }
	      id = 'F' + (++this._fontCount);
	      this._font = new PDFFont(this, src, family, id);
	      if (font = this._fontFamilies[this._font.name]) {
	        this._font = font;
	        return this;
	      }
	      if (cacheKey) {
	        this._fontFamilies[cacheKey] = this._font;
	      }
	      this._fontFamilies[this._font.name] = this._font;
	      return this;
	    },
	    fontSize: function(_fontSize) {
	      this._fontSize = _fontSize;
	      return this;
	    },
	    currentLineHeight: function(includeGap) {
	      if (includeGap == null) {
	        includeGap = false;
	      }
	      return this._font.lineHeight(this._fontSize, includeGap);
	    },
	    registerFont: function(name, src, family) {
	      this._registeredFonts[name] = {
	        src: src,
	        family: family
	      };
	      return this;
	    }
	  };

	}).call(this);


/***/ },
/* 45 */
/***/ function(module, exports, __webpack_require__) {

	/* WEBPACK VAR INJECTION */(function(Buffer, process) {// Copyright Joyent, Inc. and other Node contributors.
	//
	// Permission is hereby granted, free of charge, to any person obtaining a
	// copy of this software and associated documentation files (the
	// "Software"), to deal in the Software without restriction, including
	// without limitation the rights to use, copy, modify, merge, publish,
	// distribute, sublicense, and/or sell copies of the Software, and to permit
	// persons to whom the Software is furnished to do so, subject to the
	// following conditions:
	//
	// The above copyright notice and this permission notice shall be included
	// in all copies or substantial portions of the Software.
	//
	// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
	// OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
	// MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN
	// NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
	// DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
	// OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE
	// USE OR OTHER DEALINGS IN THE SOFTWARE.

	var Transform = __webpack_require__(55);

	var binding = __webpack_require__(50);
	var util = __webpack_require__(60);
	var assert = __webpack_require__(53).ok;

	// zlib doesn't provide these, so kludge them in following the same
	// const naming scheme zlib uses.
	binding.Z_MIN_WINDOWBITS = 8;
	binding.Z_MAX_WINDOWBITS = 15;
	binding.Z_DEFAULT_WINDOWBITS = 15;

	// fewer than 64 bytes per chunk is stupid.
	// technically it could work with as few as 8, but even 64 bytes
	// is absurdly low.  Usually a MB or more is best.
	binding.Z_MIN_CHUNK = 64;
	binding.Z_MAX_CHUNK = Infinity;
	binding.Z_DEFAULT_CHUNK = (16 * 1024);

	binding.Z_MIN_MEMLEVEL = 1;
	binding.Z_MAX_MEMLEVEL = 9;
	binding.Z_DEFAULT_MEMLEVEL = 8;

	binding.Z_MIN_LEVEL = -1;
	binding.Z_MAX_LEVEL = 9;
	binding.Z_DEFAULT_LEVEL = binding.Z_DEFAULT_COMPRESSION;

	// expose all the zlib constants
	Object.keys(binding).forEach(function(k) {
	  if (k.match(/^Z/)) exports[k] = binding[k];
	});

	// translation table for return codes.
	exports.codes = {
	  Z_OK: binding.Z_OK,
	  Z_STREAM_END: binding.Z_STREAM_END,
	  Z_NEED_DICT: binding.Z_NEED_DICT,
	  Z_ERRNO: binding.Z_ERRNO,
	  Z_STREAM_ERROR: binding.Z_STREAM_ERROR,
	  Z_DATA_ERROR: binding.Z_DATA_ERROR,
	  Z_MEM_ERROR: binding.Z_MEM_ERROR,
	  Z_BUF_ERROR: binding.Z_BUF_ERROR,
	  Z_VERSION_ERROR: binding.Z_VERSION_ERROR
	};

	Object.keys(exports.codes).forEach(function(k) {
	  exports.codes[exports.codes[k]] = k;
	});

	exports.Deflate = Deflate;
	exports.Inflate = Inflate;
	exports.Gzip = Gzip;
	exports.Gunzip = Gunzip;
	exports.DeflateRaw = DeflateRaw;
	exports.InflateRaw = InflateRaw;
	exports.Unzip = Unzip;

	exports.createDeflate = function(o) {
	  return new Deflate(o);
	};

	exports.createInflate = function(o) {
	  return new Inflate(o);
	};

	exports.createDeflateRaw = function(o) {
	  return new DeflateRaw(o);
	};

	exports.createInflateRaw = function(o) {
	  return new InflateRaw(o);
	};

	exports.createGzip = function(o) {
	  return new Gzip(o);
	};

	exports.createGunzip = function(o) {
	  return new Gunzip(o);
	};

	exports.createUnzip = function(o) {
	  return new Unzip(o);
	};


	// Convenience methods.
	// compress/decompress a string or buffer in one step.
	exports.deflate = function(buffer, opts, callback) {
	  if (typeof opts === 'function') {
	    callback = opts;
	    opts = {};
	  }
	  return zlibBuffer(new Deflate(opts), buffer, callback);
	};

	exports.deflateSync = function(buffer, opts) {
	  return zlibBufferSync(new Deflate(opts), buffer);
	};

	exports.gzip = function(buffer, opts, callback) {
	  if (typeof opts === 'function') {
	    callback = opts;
	    opts = {};
	  }
	  return zlibBuffer(new Gzip(opts), buffer, callback);
	};

	exports.gzipSync = function(buffer, opts) {
	  return zlibBufferSync(new Gzip(opts), buffer);
	};

	exports.deflateRaw = function(buffer, opts, callback) {
	  if (typeof opts === 'function') {
	    callback = opts;
	    opts = {};
	  }
	  return zlibBuffer(new DeflateRaw(opts), buffer, callback);
	};

	exports.deflateRawSync = function(buffer, opts) {
	  return zlibBufferSync(new DeflateRaw(opts), buffer);
	};

	exports.unzip = function(buffer, opts, callback) {
	  if (typeof opts === 'function') {
	    callback = opts;
	    opts = {};
	  }
	  return zlibBuffer(new Unzip(opts), buffer, callback);
	};

	exports.unzipSync = function(buffer, opts) {
	  return zlibBufferSync(new Unzip(opts), buffer);
	};

	exports.inflate = function(buffer, opts, callback) {
	  if (typeof opts === 'function') {
	    callback = opts;
	    opts = {};
	  }
	  return zlibBuffer(new Inflate(opts), buffer, callback);
	};

	exports.inflateSync = function(buffer, opts) {
	  return zlibBufferSync(new Inflate(opts), buffer);
	};

	exports.gunzip = function(buffer, opts, callback) {
	  if (typeof opts === 'function') {
	    callback = opts;
	    opts = {};
	  }
	  return zlibBuffer(new Gunzip(opts), buffer, callback);
	};

	exports.gunzipSync = function(buffer, opts) {
	  return zlibBufferSync(new Gunzip(opts), buffer);
	};

	exports.inflateRaw = function(buffer, opts, callback) {
	  if (typeof opts === 'function') {
	    callback = opts;
	    opts = {};
	  }
	  return zlibBuffer(new InflateRaw(opts), buffer, callback);
	};

	exports.inflateRawSync = function(buffer, opts) {
	  return zlibBufferSync(new InflateRaw(opts), buffer);
	};

	function zlibBuffer(engine, buffer, callback) {
	  var buffers = [];
	  var nread = 0;

	  engine.on('error', onError);
	  engine.on('end', onEnd);

	  engine.end(buffer);
	  flow();

	  function flow() {
	    var chunk;
	    while (null !== (chunk = engine.read())) {
	      buffers.push(chunk);
	      nread += chunk.length;
	    }
	    engine.once('readable', flow);
	  }

	  function onError(err) {
	    engine.removeListener('end', onEnd);
	    engine.removeListener('readable', flow);
	    callback(err);
	  }

	  function onEnd() {
	    var buf = Buffer.concat(buffers, nread);
	    buffers = [];
	    callback(null, buf);
	    engine.close();
	  }
	}

	function zlibBufferSync(engine, buffer) {
	  if (typeof buffer === 'string')
	    buffer = new Buffer(buffer);
	  if (!Buffer.isBuffer(buffer))
	    throw new TypeError('Not a string or buffer');

	  var flushFlag = binding.Z_FINISH;

	  return engine._processChunk(buffer, flushFlag);
	}

	// generic zlib
	// minimal 2-byte header
	function Deflate(opts) {
	  if (!(this instanceof Deflate)) return new Deflate(opts);
	  Zlib.call(this, opts, binding.DEFLATE);
	}

	function Inflate(opts) {
	  if (!(this instanceof Inflate)) return new Inflate(opts);
	  Zlib.call(this, opts, binding.INFLATE);
	}



	// gzip - bigger header, same deflate compression
	function Gzip(opts) {
	  if (!(this instanceof Gzip)) return new Gzip(opts);
	  Zlib.call(this, opts, binding.GZIP);
	}

	function Gunzip(opts) {
	  if (!(this instanceof Gunzip)) return new Gunzip(opts);
	  Zlib.call(this, opts, binding.GUNZIP);
	}



	// raw - no header
	function DeflateRaw(opts) {
	  if (!(this instanceof DeflateRaw)) return new DeflateRaw(opts);
	  Zlib.call(this, opts, binding.DEFLATERAW);
	}

	function InflateRaw(opts) {
	  if (!(this instanceof InflateRaw)) return new InflateRaw(opts);
	  Zlib.call(this, opts, binding.INFLATERAW);
	}


	// auto-detect header.
	function Unzip(opts) {
	  if (!(this instanceof Unzip)) return new Unzip(opts);
	  Zlib.call(this, opts, binding.UNZIP);
	}


	// the Zlib class they all inherit from
	// This thing manages the queue of requests, and returns
	// true or false if there is anything in the queue when
	// you call the .write() method.

	function Zlib(opts, mode) {
	  this._opts = opts = opts || {};
	  this._chunkSize = opts.chunkSize || exports.Z_DEFAULT_CHUNK;

	  Transform.call(this, opts);

	  if (opts.flush) {
	    if (opts.flush !== binding.Z_NO_FLUSH &&
	        opts.flush !== binding.Z_PARTIAL_FLUSH &&
	        opts.flush !== binding.Z_SYNC_FLUSH &&
	        opts.flush !== binding.Z_FULL_FLUSH &&
	        opts.flush !== binding.Z_FINISH &&
	        opts.flush !== binding.Z_BLOCK) {
	      throw new Error('Invalid flush flag: ' + opts.flush);
	    }
	  }
	  this._flushFlag = opts.flush || binding.Z_NO_FLUSH;

	  if (opts.chunkSize) {
	    if (opts.chunkSize < exports.Z_MIN_CHUNK ||
	        opts.chunkSize > exports.Z_MAX_CHUNK) {
	      throw new Error('Invalid chunk size: ' + opts.chunkSize);
	    }
	  }

	  if (opts.windowBits) {
	    if (opts.windowBits < exports.Z_MIN_WINDOWBITS ||
	        opts.windowBits > exports.Z_MAX_WINDOWBITS) {
	      throw new Error('Invalid windowBits: ' + opts.windowBits);
	    }
	  }

	  if (opts.level) {
	    if (opts.level < exports.Z_MIN_LEVEL ||
	        opts.level > exports.Z_MAX_LEVEL) {
	      throw new Error('Invalid compression level: ' + opts.level);
	    }
	  }

	  if (opts.memLevel) {
	    if (opts.memLevel < exports.Z_MIN_MEMLEVEL ||
	        opts.memLevel > exports.Z_MAX_MEMLEVEL) {
	      throw new Error('Invalid memLevel: ' + opts.memLevel);
	    }
	  }

	  if (opts.strategy) {
	    if (opts.strategy != exports.Z_FILTERED &&
	        opts.strategy != exports.Z_HUFFMAN_ONLY &&
	        opts.strategy != exports.Z_RLE &&
	        opts.strategy != exports.Z_FIXED &&
	        opts.strategy != exports.Z_DEFAULT_STRATEGY) {
	      throw new Error('Invalid strategy: ' + opts.strategy);
	    }
	  }

	  if (opts.dictionary) {
	    if (!Buffer.isBuffer(opts.dictionary)) {
	      throw new Error('Invalid dictionary: it should be a Buffer instance');
	    }
	  }

	  this._binding = new binding.Zlib(mode);

	  var self = this;
	  this._hadError = false;
	  this._binding.onerror = function(message, errno) {
	    // there is no way to cleanly recover.
	    // continuing only obscures problems.
	    self._binding = null;
	    self._hadError = true;

	    var error = new Error(message);
	    error.errno = errno;
	    error.code = exports.codes[errno];
	    self.emit('error', error);
	  };

	  var level = exports.Z_DEFAULT_COMPRESSION;
	  if (typeof opts.level === 'number') level = opts.level;

	  var strategy = exports.Z_DEFAULT_STRATEGY;
	  if (typeof opts.strategy === 'number') strategy = opts.strategy;

	  this._binding.init(opts.windowBits || exports.Z_DEFAULT_WINDOWBITS,
	                     level,
	                     opts.memLevel || exports.Z_DEFAULT_MEMLEVEL,
	                     strategy,
	                     opts.dictionary);

	  this._buffer = new Buffer(this._chunkSize);
	  this._offset = 0;
	  this._closed = false;
	  this._level = level;
	  this._strategy = strategy;

	  this.once('end', this.close);
	}

	util.inherits(Zlib, Transform);

	Zlib.prototype.params = function(level, strategy, callback) {
	  if (level < exports.Z_MIN_LEVEL ||
	      level > exports.Z_MAX_LEVEL) {
	    throw new RangeError('Invalid compression level: ' + level);
	  }
	  if (strategy != exports.Z_FILTERED &&
	      strategy != exports.Z_HUFFMAN_ONLY &&
	      strategy != exports.Z_RLE &&
	      strategy != exports.Z_FIXED &&
	      strategy != exports.Z_DEFAULT_STRATEGY) {
	    throw new TypeError('Invalid strategy: ' + strategy);
	  }

	  if (this._level !== level || this._strategy !== strategy) {
	    var self = this;
	    this.flush(binding.Z_SYNC_FLUSH, function() {
	      self._binding.params(level, strategy);
	      if (!self._hadError) {
	        self._level = level;
	        self._strategy = strategy;
	        if (callback) callback();
	      }
	    });
	  } else {
	    process.nextTick(callback);
	  }
	};

	Zlib.prototype.reset = function() {
	  return this._binding.reset();
	};

	// This is the _flush function called by the transform class,
	// internally, when the last chunk has been written.
	Zlib.prototype._flush = function(callback) {
	  this._transform(new Buffer(0), '', callback);
	};

	Zlib.prototype.flush = function(kind, callback) {
	  var ws = this._writableState;

	  if (typeof kind === 'function' || (kind === void 0 && !callback)) {
	    callback = kind;
	    kind = binding.Z_FULL_FLUSH;
	  }

	  if (ws.ended) {
	    if (callback)
	      process.nextTick(callback);
	  } else if (ws.ending) {
	    if (callback)
	      this.once('end', callback);
	  } else if (ws.needDrain) {
	    var self = this;
	    this.once('drain', function() {
	      self.flush(callback);
	    });
	  } else {
	    this._flushFlag = kind;
	    this.write(new Buffer(0), '', callback);
	  }
	};

	Zlib.prototype.close = function(callback) {
	  if (callback)
	    process.nextTick(callback);

	  if (this._closed)
	    return;

	  this._closed = true;

	  this._binding.close();

	  var self = this;
	  process.nextTick(function() {
	    self.emit('close');
	  });
	};

	Zlib.prototype._transform = function(chunk, encoding, cb) {
	  var flushFlag;
	  var ws = this._writableState;
	  var ending = ws.ending || ws.ended;
	  var last = ending && (!chunk || ws.length === chunk.length);

	  if (!chunk === null && !Buffer.isBuffer(chunk))
	    return cb(new Error('invalid input'));

	  // If it's the last chunk, or a final flush, we use the Z_FINISH flush flag.
	  // If it's explicitly flushing at some other time, then we use
	  // Z_FULL_FLUSH. Otherwise, use Z_NO_FLUSH for maximum compression
	  // goodness.
	  if (last)
	    flushFlag = binding.Z_FINISH;
	  else {
	    flushFlag = this._flushFlag;
	    // once we've flushed the last of the queue, stop flushing and
	    // go back to the normal behavior.
	    if (chunk.length >= ws.length) {
	      this._flushFlag = this._opts.flush || binding.Z_NO_FLUSH;
	    }
	  }

	  var self = this;
	  this._processChunk(chunk, flushFlag, cb);
	};

	Zlib.prototype._processChunk = function(chunk, flushFlag, cb) {
	  var availInBefore = chunk && chunk.length;
	  var availOutBefore = this._chunkSize - this._offset;
	  var inOff = 0;

	  var self = this;

	  var async = typeof cb === 'function';

	  if (!async) {
	    var buffers = [];
	    var nread = 0;

	    var error;
	    this.on('error', function(er) {
	      error = er;
	    });

	    do {
	      var res = this._binding.writeSync(flushFlag,
	                                        chunk, // in
	                                        inOff, // in_off
	                                        availInBefore, // in_len
	                                        this._buffer, // out
	                                        this._offset, //out_off
	                                        availOutBefore); // out_len
	    } while (!this._hadError && callback(res[0], res[1]));

	    if (this._hadError) {
	      throw error;
	    }

	    var buf = Buffer.concat(buffers, nread);
	    this.close();

	    return buf;
	  }

	  var req = this._binding.write(flushFlag,
	                                chunk, // in
	                                inOff, // in_off
	                                availInBefore, // in_len
	                                this._buffer, // out
	                                this._offset, //out_off
	                                availOutBefore); // out_len

	  req.buffer = chunk;
	  req.callback = callback;

	  function callback(availInAfter, availOutAfter) {
	    if (self._hadError)
	      return;

	    var have = availOutBefore - availOutAfter;
	    assert(have >= 0, 'have should not go down');

	    if (have > 0) {
	      var out = self._buffer.slice(self._offset, self._offset + have);
	      self._offset += have;
	      // serve some output to the consumer.
	      if (async) {
	        self.push(out);
	      } else {
	        buffers.push(out);
	        nread += out.length;
	      }
	    }

	    // exhausted the output buffer, or used all the input create a new one.
	    if (availOutAfter === 0 || self._offset >= self._chunkSize) {
	      availOutBefore = self._chunkSize;
	      self._offset = 0;
	      self._buffer = new Buffer(self._chunkSize);
	    }

	    if (availOutAfter === 0) {
	      // Not actually done.  Need to reprocess.
	      // Also, update the availInBefore to the availInAfter value,
	      // so that if we have to hit it a third (fourth, etc.) time,
	      // it'll have the correct byte counts.
	      inOff += (availInBefore - availInAfter);
	      availInBefore = availInAfter;

	      if (!async)
	        return true;

	      var newReq = self._binding.write(flushFlag,
	                                       chunk,
	                                       inOff,
	                                       availInBefore,
	                                       self._buffer,
	                                       self._offset,
	                                       self._chunkSize);
	      newReq.callback = callback; // this same function
	      newReq.buffer = chunk;
	      return;
	    }

	    if (!async)
	      return false;

	    // finished with the chunk.
	    cb();
	  }
	};

	util.inherits(Deflate, Zlib);
	util.inherits(Inflate, Zlib);
	util.inherits(Gzip, Zlib);
	util.inherits(Gunzip, Zlib);
	util.inherits(DeflateRaw, Zlib);
	util.inherits(InflateRaw, Zlib);
	util.inherits(Unzip, Zlib);

	/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(4).Buffer, __webpack_require__(61)))

/***/ },
/* 46 */
/***/ function(module, exports, __webpack_require__) {

	// Copyright Joyent, Inc. and other Node contributors.
	//
	// Permission is hereby granted, free of charge, to any person obtaining a
	// copy of this software and associated documentation files (the
	// "Software"), to deal in the Software without restriction, including
	// without limitation the rights to use, copy, modify, merge, publish,
	// distribute, sublicense, and/or sell copies of the Software, and to permit
	// persons to whom the Software is furnished to do so, subject to the
	// following conditions:
	//
	// The above copyright notice and this permission notice shall be included
	// in all copies or substantial portions of the Software.
	//
	// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
	// OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
	// MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN
	// NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
	// DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
	// OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE
	// USE OR OTHER DEALINGS IN THE SOFTWARE.

	module.exports = Stream;

	var EE = __webpack_require__(54).EventEmitter;
	var inherits = __webpack_require__(62);

	inherits(Stream, EE);
	Stream.Readable = __webpack_require__(56);
	Stream.Writable = __webpack_require__(57);
	Stream.Duplex = __webpack_require__(58);
	Stream.Transform = __webpack_require__(55);
	Stream.PassThrough = __webpack_require__(59);

	// Backwards-compat with node 0.4.x
	Stream.Stream = Stream;



	// old-style streams.  Note that the pipe method (the only relevant
	// part of this class) is overridden in the Readable class.

	function Stream() {
	  EE.call(this);
	}

	Stream.prototype.pipe = function(dest, options) {
	  var source = this;

	  function ondata(chunk) {
	    if (dest.writable) {
	      if (false === dest.write(chunk) && source.pause) {
	        source.pause();
	      }
	    }
	  }

	  source.on('data', ondata);

	  function ondrain() {
	    if (source.readable && source.resume) {
	      source.resume();
	    }
	  }

	  dest.on('drain', ondrain);

	  // If the 'end' option is not supplied, dest.end() will be called when
	  // source gets the 'end' or 'close' events.  Only dest.end() once.
	  if (!dest._isStdio && (!options || options.end !== false)) {
	    source.on('end', onend);
	    source.on('close', onclose);
	  }

	  var didOnEnd = false;
	  function onend() {
	    if (didOnEnd) return;
	    didOnEnd = true;

	    dest.end();
	  }


	  function onclose() {
	    if (didOnEnd) return;
	    didOnEnd = true;

	    if (typeof dest.destroy === 'function') dest.destroy();
	  }

	  // don't leave dangling pipes when there are errors.
	  function onerror(er) {
	    cleanup();
	    if (EE.listenerCount(this, 'error') === 0) {
	      throw er; // Unhandled stream error in pipe.
	    }
	  }

	  source.on('error', onerror);
	  dest.on('error', onerror);

	  // remove all the event listeners that were added.
	  function cleanup() {
	    source.removeListener('data', ondata);
	    dest.removeListener('drain', ondrain);

	    source.removeListener('end', onend);
	    source.removeListener('close', onclose);

	    source.removeListener('error', onerror);
	    dest.removeListener('error', onerror);

	    source.removeListener('end', cleanup);
	    source.removeListener('close', cleanup);

	    dest.removeListener('close', cleanup);
	  }

	  source.on('end', cleanup);
	  source.on('close', cleanup);

	  dest.on('close', cleanup);

	  dest.emit('pipe', source);

	  // Allow for unix-like usage: A.pipe(B).pipe(C)
	  return dest;
	};


/***/ },
/* 47 */
/***/ function(module, exports, __webpack_require__) {

	// Generated by CoffeeScript 1.7.1
	(function() {
	  var SVGPath;

	  SVGPath = (function() {
	    var apply, arcToSegments, cx, cy, parameters, parse, px, py, runners, segmentToBezier, solveArc, sx, sy;

	    function SVGPath() {}

	    SVGPath.apply = function(doc, path) {
	      var commands;
	      commands = parse(path);
	      return apply(commands, doc);
	    };

	    parameters = {
	      A: 7,
	      a: 7,
	      C: 6,
	      c: 6,
	      H: 1,
	      h: 1,
	      L: 2,
	      l: 2,
	      M: 2,
	      m: 2,
	      Q: 4,
	      q: 4,
	      S: 4,
	      s: 4,
	      T: 2,
	      t: 2,
	      V: 1,
	      v: 1,
	      Z: 0,
	      z: 0
	    };

	    parse = function(path) {
	      var args, c, cmd, curArg, foundDecimal, params, ret, _i, _len;
	      ret = [];
	      args = [];
	      curArg = "";
	      foundDecimal = false;
	      params = 0;
	      for (_i = 0, _len = path.length; _i < _len; _i++) {
	        c = path[_i];
	        if (parameters[c] != null) {
	          params = parameters[c];
	          if (cmd) {
	            if (curArg.length > 0) {
	              args[args.length] = +curArg;
	            }
	            ret[ret.length] = {
	              cmd: cmd,
	              args: args
	            };
	            args = [];
	            curArg = "";
	            foundDecimal = false;
	          }
	          cmd = c;
	        } else if ((c === " " || c === ",") || (c === "-" && curArg.length > 0 && curArg[curArg.length - 1] !== 'e') || (c === "." && foundDecimal)) {
	          if (curArg.length === 0) {
	            continue;
	          }
	          if (args.length === params) {
	            ret[ret.length] = {
	              cmd: cmd,
	              args: args
	            };
	            args = [+curArg];
	            if (cmd === "M") {
	              cmd = "L";
	            }
	            if (cmd === "m") {
	              cmd = "l";
	            }
	          } else {
	            args[args.length] = +curArg;
	          }
	          foundDecimal = c === ".";
	          curArg = c === '-' || c === '.' ? c : '';
	        } else {
	          curArg += c;
	          if (c === '.') {
	            foundDecimal = true;
	          }
	        }
	      }
	      if (curArg.length > 0) {
	        if (args.length === params) {
	          ret[ret.length] = {
	            cmd: cmd,
	            args: args
	          };
	          args = [+curArg];
	          if (cmd === "M") {
	            cmd = "L";
	          }
	          if (cmd === "m") {
	            cmd = "l";
	          }
	        } else {
	          args[args.length] = +curArg;
	        }
	      }
	      ret[ret.length] = {
	        cmd: cmd,
	        args: args
	      };
	      return ret;
	    };

	    cx = cy = px = py = sx = sy = 0;

	    apply = function(commands, doc) {
	      var c, i, _i, _len, _name;
	      cx = cy = px = py = sx = sy = 0;
	      for (i = _i = 0, _len = commands.length; _i < _len; i = ++_i) {
	        c = commands[i];
	        if (typeof runners[_name = c.cmd] === "function") {
	          runners[_name](doc, c.args);
	        }
	      }
	      return cx = cy = px = py = 0;
	    };

	    runners = {
	      M: function(doc, a) {
	        cx = a[0];
	        cy = a[1];
	        px = py = null;
	        sx = cx;
	        sy = cy;
	        return doc.moveTo(cx, cy);
	      },
	      m: function(doc, a) {
	        cx += a[0];
	        cy += a[1];
	        px = py = null;
	        sx = cx;
	        sy = cy;
	        return doc.moveTo(cx, cy);
	      },
	      C: function(doc, a) {
	        cx = a[4];
	        cy = a[5];
	        px = a[2];
	        py = a[3];
	        return doc.bezierCurveTo.apply(doc, a);
	      },
	      c: function(doc, a) {
	        doc.bezierCurveTo(a[0] + cx, a[1] + cy, a[2] + cx, a[3] + cy, a[4] + cx, a[5] + cy);
	        px = cx + a[2];
	        py = cy + a[3];
	        cx += a[4];
	        return cy += a[5];
	      },
	      S: function(doc, a) {
	        if (px === null) {
	          px = cx;
	          py = cy;
	        }
	        doc.bezierCurveTo(cx - (px - cx), cy - (py - cy), a[0], a[1], a[2], a[3]);
	        px = a[0];
	        py = a[1];
	        cx = a[2];
	        return cy = a[3];
	      },
	      s: function(doc, a) {
	        if (px === null) {
	          px = cx;
	          py = cy;
	        }
	        doc.bezierCurveTo(cx - (px - cx), cy - (py - cy), cx + a[0], cy + a[1], cx + a[2], cy + a[3]);
	        px = cx + a[0];
	        py = cy + a[1];
	        cx += a[2];
	        return cy += a[3];
	      },
	      Q: function(doc, a) {
	        px = a[0];
	        py = a[1];
	        cx = a[2];
	        cy = a[3];
	        return doc.quadraticCurveTo(a[0], a[1], cx, cy);
	      },
	      q: function(doc, a) {
	        doc.quadraticCurveTo(a[0] + cx, a[1] + cy, a[2] + cx, a[3] + cy);
	        px = cx + a[0];
	        py = cy + a[1];
	        cx += a[2];
	        return cy += a[3];
	      },
	      T: function(doc, a) {
	        if (px === null) {
	          px = cx;
	          py = cy;
	        } else {
	          px = cx - (px - cx);
	          py = cy - (py - cy);
	        }
	        doc.quadraticCurveTo(px, py, a[0], a[1]);
	        px = cx - (px - cx);
	        py = cy - (py - cy);
	        cx = a[0];
	        return cy = a[1];
	      },
	      t: function(doc, a) {
	        if (px === null) {
	          px = cx;
	          py = cy;
	        } else {
	          px = cx - (px - cx);
	          py = cy - (py - cy);
	        }
	        doc.quadraticCurveTo(px, py, cx + a[0], cy + a[1]);
	        cx += a[0];
	        return cy += a[1];
	      },
	      A: function(doc, a) {
	        solveArc(doc, cx, cy, a);
	        cx = a[5];
	        return cy = a[6];
	      },
	      a: function(doc, a) {
	        a[5] += cx;
	        a[6] += cy;
	        solveArc(doc, cx, cy, a);
	        cx = a[5];
	        return cy = a[6];
	      },
	      L: function(doc, a) {
	        cx = a[0];
	        cy = a[1];
	        px = py = null;
	        return doc.lineTo(cx, cy);
	      },
	      l: function(doc, a) {
	        cx += a[0];
	        cy += a[1];
	        px = py = null;
	        return doc.lineTo(cx, cy);
	      },
	      H: function(doc, a) {
	        cx = a[0];
	        px = py = null;
	        return doc.lineTo(cx, cy);
	      },
	      h: function(doc, a) {
	        cx += a[0];
	        px = py = null;
	        return doc.lineTo(cx, cy);
	      },
	      V: function(doc, a) {
	        cy = a[0];
	        px = py = null;
	        return doc.lineTo(cx, cy);
	      },
	      v: function(doc, a) {
	        cy += a[0];
	        px = py = null;
	        return doc.lineTo(cx, cy);
	      },
	      Z: function(doc) {
	        doc.closePath();
	        cx = sx;
	        return cy = sy;
	      },
	      z: function(doc) {
	        doc.closePath();
	        cx = sx;
	        return cy = sy;
	      }
	    };

	    solveArc = function(doc, x, y, coords) {
	      var bez, ex, ey, large, rot, rx, ry, seg, segs, sweep, _i, _len, _results;
	      rx = coords[0], ry = coords[1], rot = coords[2], large = coords[3], sweep = coords[4], ex = coords[5], ey = coords[6];
	      segs = arcToSegments(ex, ey, rx, ry, large, sweep, rot, x, y);
	      _results = [];
	      for (_i = 0, _len = segs.length; _i < _len; _i++) {
	        seg = segs[_i];
	        bez = segmentToBezier.apply(null, seg);
	        _results.push(doc.bezierCurveTo.apply(doc, bez));
	      }
	      return _results;
	    };

	    arcToSegments = function(x, y, rx, ry, large, sweep, rotateX, ox, oy) {
	      var a00, a01, a10, a11, cos_th, d, i, pl, result, segments, sfactor, sfactor_sq, sin_th, th, th0, th1, th2, th3, th_arc, x0, x1, xc, y0, y1, yc, _i;
	      th = rotateX * (Math.PI / 180);
	      sin_th = Math.sin(th);
	      cos_th = Math.cos(th);
	      rx = Math.abs(rx);
	      ry = Math.abs(ry);
	      px = cos_th * (ox - x) * 0.5 + sin_th * (oy - y) * 0.5;
	      py = cos_th * (oy - y) * 0.5 - sin_th * (ox - x) * 0.5;
	      pl = (px * px) / (rx * rx) + (py * py) / (ry * ry);
	      if (pl > 1) {
	        pl = Math.sqrt(pl);
	        rx *= pl;
	        ry *= pl;
	      }
	      a00 = cos_th / rx;
	      a01 = sin_th / rx;
	      a10 = (-sin_th) / ry;
	      a11 = cos_th / ry;
	      x0 = a00 * ox + a01 * oy;
	      y0 = a10 * ox + a11 * oy;
	      x1 = a00 * x + a01 * y;
	      y1 = a10 * x + a11 * y;
	      d = (x1 - x0) * (x1 - x0) + (y1 - y0) * (y1 - y0);
	      sfactor_sq = 1 / d - 0.25;
	      if (sfactor_sq < 0) {
	        sfactor_sq = 0;
	      }
	      sfactor = Math.sqrt(sfactor_sq);
	      if (sweep === large) {
	        sfactor = -sfactor;
	      }
	      xc = 0.5 * (x0 + x1) - sfactor * (y1 - y0);
	      yc = 0.5 * (y0 + y1) + sfactor * (x1 - x0);
	      th0 = Math.atan2(y0 - yc, x0 - xc);
	      th1 = Math.atan2(y1 - yc, x1 - xc);
	      th_arc = th1 - th0;
	      if (th_arc < 0 && sweep === 1) {
	        th_arc += 2 * Math.PI;
	      } else if (th_arc > 0 && sweep === 0) {
	        th_arc -= 2 * Math.PI;
	      }
	      segments = Math.ceil(Math.abs(th_arc / (Math.PI * 0.5 + 0.001)));
	      result = [];
	      for (i = _i = 0; 0 <= segments ? _i < segments : _i > segments; i = 0 <= segments ? ++_i : --_i) {
	        th2 = th0 + i * th_arc / segments;
	        th3 = th0 + (i + 1) * th_arc / segments;
	        result[i] = [xc, yc, th2, th3, rx, ry, sin_th, cos_th];
	      }
	      return result;
	    };

	    segmentToBezier = function(cx, cy, th0, th1, rx, ry, sin_th, cos_th) {
	      var a00, a01, a10, a11, t, th_half, x1, x2, x3, y1, y2, y3;
	      a00 = cos_th * rx;
	      a01 = -sin_th * ry;
	      a10 = sin_th * rx;
	      a11 = cos_th * ry;
	      th_half = 0.5 * (th1 - th0);
	      t = (8 / 3) * Math.sin(th_half * 0.5) * Math.sin(th_half * 0.5) / Math.sin(th_half);
	      x1 = cx + Math.cos(th0) - t * Math.sin(th0);
	      y1 = cy + Math.sin(th0) + t * Math.cos(th0);
	      x3 = cx + Math.cos(th1);
	      y3 = cy + Math.sin(th1);
	      x2 = x3 + t * Math.sin(th1);
	      y2 = y3 - t * Math.cos(th1);
	      return [a00 * x1 + a01 * y1, a10 * x1 + a11 * y1, a00 * x2 + a01 * y2, a10 * x2 + a11 * y2, a00 * x3 + a01 * y3, a10 * x3 + a11 * y3];
	    };

	    return SVGPath;

	  })();

	  module.exports = SVGPath;

	}).call(this);


/***/ },
/* 48 */
/***/ function(module, exports, __webpack_require__) {

	// Generated by CoffeeScript 1.7.1
	(function() {
	  var EventEmitter, LineBreaker, LineWrapper,
	    __hasProp = {}.hasOwnProperty,
	    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

	  EventEmitter = __webpack_require__(54).EventEmitter;

	  LineBreaker = __webpack_require__(66);

	  LineWrapper = (function(_super) {
	    __extends(LineWrapper, _super);

	    function LineWrapper(document, options) {
	      var _ref;
	      this.document = document;
	      this.indent = options.indent || 0;
	      this.characterSpacing = options.characterSpacing || 0;
	      this.wordSpacing = options.wordSpacing === 0;
	      this.columns = options.columns || 1;
	      this.columnGap = (_ref = options.columnGap) != null ? _ref : 18;
	      this.lineWidth = (options.width - (this.columnGap * (this.columns - 1))) / this.columns;
	      this.spaceLeft = this.lineWidth;
	      this.startX = this.document.x;
	      this.startY = this.document.y;
	      this.column = 1;
	      this.ellipsis = options.ellipsis;
	      this.continuedX = 0;
	      if (options.height != null) {
	        this.height = options.height;
	        this.maxY = this.startY + options.height;
	      } else {
	        this.maxY = this.document.page.maxY();
	      }
	      this.on('firstLine', (function(_this) {
	        return function(options) {
	          var indent;
	          indent = _this.continuedX || _this.indent;
	          _this.document.x += indent;
	          _this.lineWidth -= indent;
	          return _this.once('line', function() {
	            _this.document.x -= indent;
	            _this.lineWidth += indent;
	            if (options.continued && !_this.continuedX) {
	              _this.continuedX = _this.indent;
	            }
	            if (!options.continued) {
	              return _this.continuedX = 0;
	            }
	          });
	        };
	      })(this));
	      this.on('lastLine', (function(_this) {
	        return function(options) {
	          var align;
	          align = options.align;
	          if (align === 'justify') {
	            options.align = 'left';
	          }
	          _this.lastLine = true;
	          return _this.once('line', function() {
	            _this.document.y += options.paragraphGap || 0;
	            options.align = align;
	            return _this.lastLine = false;
	          });
	        };
	      })(this));
	    }

	    LineWrapper.prototype.wordWidth = function(word) {
	      return this.document.widthOfString(word, this) + this.characterSpacing + this.wordSpacing;
	    };

	    LineWrapper.prototype.eachWord = function(text, fn) {
	      var bk, breaker, fbk, l, last, lbk, shouldContinue, w, word, wordWidths;
	      breaker = new LineBreaker(text);
	      last = null;
	      wordWidths = {};
	      while (bk = breaker.nextBreak()) {
	        word = text.slice((last != null ? last.position : void 0) || 0, bk.position);
	        w = wordWidths[word] != null ? wordWidths[word] : wordWidths[word] = this.wordWidth(word);
	        if (w > this.lineWidth + this.continuedX) {
	          lbk = last;
	          fbk = {};
	          while (word.length) {
	            l = word.length;
	            while (w > this.spaceLeft) {
	              w = this.wordWidth(word.slice(0, --l));
	            }
	            fbk.required = l < word.length;
	            shouldContinue = fn(word.slice(0, l), w, fbk, lbk);
	            lbk = {
	              required: false
	            };
	            word = word.slice(l);
	            w = this.wordWidth(word);
	            if (shouldContinue === false) {
	              break;
	            }
	          }
	        } else {
	          shouldContinue = fn(word, w, bk, last);
	        }
	        if (shouldContinue === false) {
	          break;
	        }
	        last = bk;
	      }
	    };

	    LineWrapper.prototype.wrap = function(text, options) {
	      var buffer, emitLine, lc, nextY, textWidth, wc, y;
	      if (options.indent != null) {
	        this.indent = options.indent;
	      }
	      if (options.characterSpacing != null) {
	        this.characterSpacing = options.characterSpacing;
	      }
	      if (options.wordSpacing != null) {
	        this.wordSpacing = options.wordSpacing;
	      }
	      if (options.ellipsis != null) {
	        this.ellipsis = options.ellipsis;
	      }
	      nextY = this.document.y + this.document.currentLineHeight(true);
	      if (this.document.y > this.maxY || nextY > this.maxY) {
	        this.nextSection();
	      }
	      buffer = '';
	      textWidth = 0;
	      wc = 0;
	      lc = 0;
	      y = this.document.y;
	      emitLine = (function(_this) {
	        return function() {
	          options.textWidth = textWidth + _this.wordSpacing * (wc - 1);
	          options.wordCount = wc;
	          options.lineWidth = _this.lineWidth;
	          y = _this.document.y;
	          _this.emit('line', buffer, options, _this);
	          return lc++;
	        };
	      })(this);
	      this.emit('sectionStart', options, this);
	      this.eachWord(text, (function(_this) {
	        return function(word, w, bk, last) {
	          var lh, shouldContinue;
	          if ((last == null) || last.required) {
	            _this.emit('firstLine', options, _this);
	            _this.spaceLeft = _this.lineWidth;
	          }
	          if (w <= _this.spaceLeft) {
	            buffer += word;
	            textWidth += w;
	            wc++;
	          }
	          if (bk.required || w > _this.spaceLeft) {
	            if (bk.required) {
	              _this.emit('lastLine', options, _this);
	            }
	            lh = _this.document.currentLineHeight(true);
	            if ((_this.height != null) && _this.ellipsis && _this.document.y + lh * 2 > _this.maxY && _this.column >= _this.columns) {
	              if (_this.ellipsis === true) {
	                _this.ellipsis = '…';
	              }
	              buffer = buffer.replace(/\s+$/, '');
	              textWidth = _this.wordWidth(buffer + _this.ellipsis);
	              while (textWidth > _this.lineWidth) {
	                buffer = buffer.slice(0, -1).replace(/\s+$/, '');
	                textWidth = _this.wordWidth(buffer + _this.ellipsis);
	              }
	              buffer = buffer + _this.ellipsis;
	            }
	            emitLine();
	            if (_this.document.y + lh > _this.maxY) {
	              shouldContinue = _this.nextSection();
	              if (!shouldContinue) {
	                wc = 0;
	                buffer = '';
	                return false;
	              }
	            }
	            if (bk.required) {
	              if (w > _this.spaceLeft) {
	                buffer = word;
	                textWidth = w;
	                wc = 1;
	                emitLine();
	              }
	              _this.spaceLeft = _this.lineWidth;
	              buffer = '';
	              textWidth = 0;
	              return wc = 0;
	            } else {
	              _this.spaceLeft = _this.lineWidth - w;
	              buffer = word;
	              textWidth = w;
	              return wc = 1;
	            }
	          } else {
	            return _this.spaceLeft -= w;
	          }
	        };
	      })(this));
	      if (wc > 0) {
	        this.emit('lastLine', options, this);
	        emitLine();
	      }
	      this.emit('sectionEnd', options, this);
	      if (options.continued === true) {
	        if (lc > 1) {
	          this.continuedX = 0;
	        }
	        this.continuedX += options.textWidth;
	        return this.document.y = y;
	      } else {
	        return this.document.x = this.startX;
	      }
	    };

	    LineWrapper.prototype.nextSection = function(options) {
	      var _ref;
	      this.emit('sectionEnd', options, this);
	      if (++this.column > this.columns) {
	        if (this.height != null) {
	          return false;
	        }
	        this.document.addPage();
	        this.column = 1;
	        this.startY = this.document.page.margins.top;
	        this.maxY = this.document.page.maxY();
	        this.document.x = this.startX;
	        if (this.document._fillColor) {
	          (_ref = this.document).fillColor.apply(_ref, this.document._fillColor);
	        }
	        this.emit('pageBreak', options, this);
	      } else {
	        this.document.x += this.lineWidth + this.columnGap;
	        this.document.y = this.startY;
	        this.emit('columnBreak', options, this);
	      }
	      this.emit('sectionStart', options, this);
	      return true;
	    };

	    return LineWrapper;

	  })(EventEmitter);

	  module.exports = LineWrapper;

	}).call(this);


/***/ },
/* 49 */
/***/ function(module, exports, __webpack_require__) {

	// Generated by CoffeeScript 1.7.1
	(function() {
	  var PDFGradient, PDFLinearGradient, PDFRadialGradient,
	    __hasProp = {}.hasOwnProperty,
	    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

	  PDFGradient = (function() {
	    function PDFGradient(doc) {
	      this.doc = doc;
	      this.stops = [];
	      this.embedded = false;
	      this.transform = [1, 0, 0, 1, 0, 0];
	      this._colorSpace = 'DeviceRGB';
	    }

	    PDFGradient.prototype.stop = function(pos, color, opacity) {
	      if (opacity == null) {
	        opacity = 1;
	      }
	      opacity = Math.max(0, Math.min(1, opacity));
	      this.stops.push([pos, this.doc._normalizeColor(color), opacity]);
	      return this;
	    };

	    PDFGradient.prototype.embed = function() {
	      var bounds, dx, dy, encode, fn, form, grad, group, gstate, i, last, m, m0, m1, m11, m12, m2, m21, m22, m3, m4, m5, name, pattern, resources, sMask, shader, stop, stops, v, _i, _j, _len, _ref, _ref1, _ref2;
	      if (this.embedded || this.stops.length === 0) {
	        return;
	      }
	      this.embedded = true;
	      last = this.stops[this.stops.length - 1];
	      if (last[0] < 1) {
	        this.stops.push([1, last[1], last[2]]);
	      }
	      bounds = [];
	      encode = [];
	      stops = [];
	      for (i = _i = 0, _ref = this.stops.length - 1; 0 <= _ref ? _i < _ref : _i > _ref; i = 0 <= _ref ? ++_i : --_i) {
	        encode.push(0, 1);
	        if (i + 2 !== this.stops.length) {
	          bounds.push(this.stops[i + 1][0]);
	        }
	        fn = this.doc.ref({
	          FunctionType: 2,
	          Domain: [0, 1],
	          C0: this.stops[i + 0][1],
	          C1: this.stops[i + 1][1],
	          N: 1
	        });
	        stops.push(fn);
	        fn.end();
	      }
	      if (stops.length === 1) {
	        fn = stops[0];
	      } else {
	        fn = this.doc.ref({
	          FunctionType: 3,
	          Domain: [0, 1],
	          Functions: stops,
	          Bounds: bounds,
	          Encode: encode
	        });
	        fn.end();
	      }
	      this.id = 'Sh' + (++this.doc._gradCount);
	      m = this.doc._ctm.slice();
	      m0 = m[0], m1 = m[1], m2 = m[2], m3 = m[3], m4 = m[4], m5 = m[5];
	      _ref1 = this.transform, m11 = _ref1[0], m12 = _ref1[1], m21 = _ref1[2], m22 = _ref1[3], dx = _ref1[4], dy = _ref1[5];
	      m[0] = m0 * m11 + m2 * m12;
	      m[1] = m1 * m11 + m3 * m12;
	      m[2] = m0 * m21 + m2 * m22;
	      m[3] = m1 * m21 + m3 * m22;
	      m[4] = m0 * dx + m2 * dy + m4;
	      m[5] = m1 * dx + m3 * dy + m5;
	      shader = this.shader(fn);
	      shader.end();
	      pattern = this.doc.ref({
	        Type: 'Pattern',
	        PatternType: 2,
	        Shading: shader,
	        Matrix: (function() {
	          var _j, _len, _results;
	          _results = [];
	          for (_j = 0, _len = m.length; _j < _len; _j++) {
	            v = m[_j];
	            _results.push(+v.toFixed(5));
	          }
	          return _results;
	        })()
	      });
	      this.doc.page.patterns[this.id] = pattern;
	      pattern.end();
	      if (this.stops.some(function(stop) {
	        return stop[2] < 1;
	      })) {
	        grad = this.opacityGradient();
	        grad._colorSpace = 'DeviceGray';
	        _ref2 = this.stops;
	        for (_j = 0, _len = _ref2.length; _j < _len; _j++) {
	          stop = _ref2[_j];
	          grad.stop(stop[0], [stop[2]]);
	        }
	        grad = grad.embed();
	        group = this.doc.ref({
	          Type: 'Group',
	          S: 'Transparency',
	          CS: 'DeviceGray'
	        });
	        group.end();
	        resources = this.doc.ref({
	          ProcSet: ['PDF', 'Text', 'ImageB', 'ImageC', 'ImageI'],
	          Shading: {
	            Sh1: grad.data.Shading
	          }
	        });
	        resources.end();
	        form = this.doc.ref({
	          Type: 'XObject',
	          Subtype: 'Form',
	          FormType: 1,
	          BBox: [0, 0, this.doc.page.width, this.doc.page.height],
	          Group: group,
	          Resources: resources
	        });
	        form.end("/Sh1 sh");
	        sMask = this.doc.ref({
	          Type: 'Mask',
	          S: 'Luminosity',
	          G: form
	        });
	        sMask.end();
	        gstate = this.doc.ref({
	          Type: 'ExtGState',
	          SMask: sMask
	        });
	        this.opacity_id = ++this.doc._opacityCount;
	        name = "Gs" + this.opacity_id;
	        this.doc.page.ext_gstates[name] = gstate;
	        gstate.end();
	      }
	      return pattern;
	    };

	    PDFGradient.prototype.apply = function(op) {
	      if (!this.embedded) {
	        this.embed();
	      }
	      this.doc.addContent("/" + this.id + " " + op);
	      if (this.opacity_id) {
	        this.doc.addContent("/Gs" + this.opacity_id + " gs");
	        return this.doc._sMasked = true;
	      }
	    };

	    return PDFGradient;

	  })();

	  PDFLinearGradient = (function(_super) {
	    __extends(PDFLinearGradient, _super);

	    function PDFLinearGradient(doc, x1, y1, x2, y2) {
	      this.doc = doc;
	      this.x1 = x1;
	      this.y1 = y1;
	      this.x2 = x2;
	      this.y2 = y2;
	      PDFLinearGradient.__super__.constructor.apply(this, arguments);
	    }

	    PDFLinearGradient.prototype.shader = function(fn) {
	      return this.doc.ref({
	        ShadingType: 2,
	        ColorSpace: this._colorSpace,
	        Coords: [this.x1, this.y1, this.x2, this.y2],
	        Function: fn,
	        Extend: [true, true]
	      });
	    };

	    PDFLinearGradient.prototype.opacityGradient = function() {
	      return new PDFLinearGradient(this.doc, this.x1, this.y1, this.x2, this.y2);
	    };

	    return PDFLinearGradient;

	  })(PDFGradient);

	  PDFRadialGradient = (function(_super) {
	    __extends(PDFRadialGradient, _super);

	    function PDFRadialGradient(doc, x1, y1, r1, x2, y2, r2) {
	      this.doc = doc;
	      this.x1 = x1;
	      this.y1 = y1;
	      this.r1 = r1;
	      this.x2 = x2;
	      this.y2 = y2;
	      this.r2 = r2;
	      PDFRadialGradient.__super__.constructor.apply(this, arguments);
	    }

	    PDFRadialGradient.prototype.shader = function(fn) {
	      return this.doc.ref({
	        ShadingType: 3,
	        ColorSpace: this._colorSpace,
	        Coords: [this.x1, this.y1, this.r1, this.x2, this.y2, this.r2],
	        Function: fn,
	        Extend: [true, true]
	      });
	    };

	    PDFRadialGradient.prototype.opacityGradient = function() {
	      return new PDFRadialGradient(this.doc, this.x1, this.y1, this.r1, this.x2, this.y2, this.r2);
	    };

	    return PDFRadialGradient;

	  })(PDFGradient);

	  module.exports = {
	    PDFGradient: PDFGradient,
	    PDFLinearGradient: PDFLinearGradient,
	    PDFRadialGradient: PDFRadialGradient
	  };

	}).call(this);


/***/ },
/* 50 */
/***/ function(module, exports, __webpack_require__) {

	/* WEBPACK VAR INJECTION */(function(process, Buffer) {var msg = __webpack_require__(73);
	var zstream = __webpack_require__(77);
	var zlib_deflate = __webpack_require__(74);
	var zlib_inflate = __webpack_require__(75);
	var constants = __webpack_require__(76);

	for (var key in constants) {
	  exports[key] = constants[key];
	}

	// zlib modes
	exports.NONE = 0;
	exports.DEFLATE = 1;
	exports.INFLATE = 2;
	exports.GZIP = 3;
	exports.GUNZIP = 4;
	exports.DEFLATERAW = 5;
	exports.INFLATERAW = 6;
	exports.UNZIP = 7;

	/**
	 * Emulate Node's zlib C++ layer for use by the JS layer in index.js
	 */
	function Zlib(mode) {
	  if (mode < exports.DEFLATE || mode > exports.UNZIP)
	    throw new TypeError("Bad argument");
	    
	  this.mode = mode;
	  this.init_done = false;
	  this.write_in_progress = false;
	  this.pending_close = false;
	  this.windowBits = 0;
	  this.level = 0;
	  this.memLevel = 0;
	  this.strategy = 0;
	  this.dictionary = null;
	}

	Zlib.prototype.init = function(windowBits, level, memLevel, strategy, dictionary) {
	  this.windowBits = windowBits;
	  this.level = level;
	  this.memLevel = memLevel;
	  this.strategy = strategy;
	  // dictionary not supported.
	  
	  if (this.mode === exports.GZIP || this.mode === exports.GUNZIP)
	    this.windowBits += 16;
	    
	  if (this.mode === exports.UNZIP)
	    this.windowBits += 32;
	    
	  if (this.mode === exports.DEFLATERAW || this.mode === exports.INFLATERAW)
	    this.windowBits = -this.windowBits;
	    
	  this.strm = new zstream();
	  
	  switch (this.mode) {
	    case exports.DEFLATE:
	    case exports.GZIP:
	    case exports.DEFLATERAW:
	      var status = zlib_deflate.deflateInit2(
	        this.strm,
	        this.level,
	        exports.Z_DEFLATED,
	        this.windowBits,
	        this.memLevel,
	        this.strategy
	      );
	      break;
	    case exports.INFLATE:
	    case exports.GUNZIP:
	    case exports.INFLATERAW:
	    case exports.UNZIP:
	      var status  = zlib_inflate.inflateInit2(
	        this.strm,
	        this.windowBits
	      );
	      break;
	    default:
	      throw new Error("Unknown mode " + this.mode);
	  }
	  
	  if (status !== exports.Z_OK) {
	    this._error(status);
	    return;
	  }
	  
	  this.write_in_progress = false;
	  this.init_done = true;
	};

	Zlib.prototype.params = function() {
	  throw new Error("deflateParams Not supported");
	};

	Zlib.prototype._writeCheck = function() {
	  if (!this.init_done)
	    throw new Error("write before init");
	    
	  if (this.mode === exports.NONE)
	    throw new Error("already finalized");
	    
	  if (this.write_in_progress)
	    throw new Error("write already in progress");
	    
	  if (this.pending_close)
	    throw new Error("close is pending");
	};

	Zlib.prototype.write = function(flush, input, in_off, in_len, out, out_off, out_len) {    
	  this._writeCheck();
	  this.write_in_progress = true;
	  
	  var self = this;
	  process.nextTick(function() {
	    self.write_in_progress = false;
	    var res = self._write(flush, input, in_off, in_len, out, out_off, out_len);
	    self.callback(res[0], res[1]);
	    
	    if (self.pending_close)
	      self.close();
	  });
	  
	  return this;
	};

	// set method for Node buffers, used by pako
	function bufferSet(data, offset) {
	  for (var i = 0; i < data.length; i++) {
	    this[offset + i] = data[i];
	  }
	}

	Zlib.prototype.writeSync = function(flush, input, in_off, in_len, out, out_off, out_len) {
	  this._writeCheck();
	  return this._write(flush, input, in_off, in_len, out, out_off, out_len);
	};

	Zlib.prototype._write = function(flush, input, in_off, in_len, out, out_off, out_len) {
	  this.write_in_progress = true;
	  
	  if (flush !== exports.Z_NO_FLUSH &&
	      flush !== exports.Z_PARTIAL_FLUSH &&
	      flush !== exports.Z_SYNC_FLUSH &&
	      flush !== exports.Z_FULL_FLUSH &&
	      flush !== exports.Z_FINISH &&
	      flush !== exports.Z_BLOCK) {
	    throw new Error("Invalid flush value");
	  }
	  
	  if (input == null) {
	    input = new Buffer(0);
	    in_len = 0;
	    in_off = 0;
	  }
	  
	  if (out._set)
	    out.set = out._set;
	  else
	    out.set = bufferSet;
	  
	  var strm = this.strm;
	  strm.avail_in = in_len;
	  strm.input = input;
	  strm.next_in = in_off;
	  strm.avail_out = out_len;
	  strm.output = out;
	  strm.next_out = out_off;
	  
	  switch (this.mode) {
	    case exports.DEFLATE:
	    case exports.GZIP:
	    case exports.DEFLATERAW:
	      var status = zlib_deflate.deflate(strm, flush);
	      break;
	    case exports.UNZIP:
	    case exports.INFLATE:
	    case exports.GUNZIP:
	    case exports.INFLATERAW:
	      var status = zlib_inflate.inflate(strm, flush);
	      break;
	    default:
	      throw new Error("Unknown mode " + this.mode);
	  }
	  
	  if (status !== exports.Z_STREAM_END && status !== exports.Z_OK) {
	    this._error(status);
	  }
	  
	  this.write_in_progress = false;
	  return [strm.avail_in, strm.avail_out];
	};

	Zlib.prototype.close = function() {
	  if (this.write_in_progress) {
	    this.pending_close = true;
	    return;
	  }
	  
	  this.pending_close = false;
	  
	  if (this.mode === exports.DEFLATE || this.mode === exports.GZIP || this.mode === exports.DEFLATERAW) {
	    zlib_deflate.deflateEnd(this.strm);
	  } else {
	    zlib_inflate.inflateEnd(this.strm);
	  }
	  
	  this.mode = exports.NONE;
	};

	Zlib.prototype.reset = function() {
	  switch (this.mode) {
	    case exports.DEFLATE:
	    case exports.DEFLATERAW:
	      var status = zlib_deflate.deflateReset(this.strm);
	      break;
	    case exports.INFLATE:
	    case exports.INFLATERAW:
	      var status = zlib_inflate.inflateReset(this.strm);
	      break;
	  }
	  
	  if (status !== exports.Z_OK) {
	    this._error(status);
	  }
	};

	Zlib.prototype._error = function(status) {
	  this.onerror(msg[status] + ': ' + this.strm.msg, status);
	  
	  this.write_in_progress = false;
	  if (this.pending_close)
	    this.close();
	};

	exports.Zlib = Zlib;

	/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(61), __webpack_require__(4).Buffer))

/***/ },
/* 51 */
/***/ function(module, exports, __webpack_require__) {

	/* WEBPACK VAR INJECTION */(function(Buffer) {// Generated by CoffeeScript 1.4.0

	/*
	# MIT LICENSE
	# Copyright (c) 2011 Devon Govett
	# 
	# Permission is hereby granted, free of charge, to any person obtaining a copy of this 
	# software and associated documentation files (the "Software"), to deal in the Software 
	# without restriction, including without limitation the rights to use, copy, modify, merge, 
	# publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons 
	# to whom the Software is furnished to do so, subject to the following conditions:
	# 
	# The above copyright notice and this permission notice shall be included in all copies or 
	# substantial portions of the Software.
	# 
	# THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING 
	# BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND 
	# NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, 
	# DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, 
	# OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
	*/


	(function() {
	  var PNG, fs, zlib;

	  fs = __webpack_require__(10);

	  zlib = __webpack_require__(45);

	  module.exports = PNG = (function() {

	    PNG.decode = function(path, fn) {
	      return fs.readFile(path, function(err, file) {
	        var png;
	        png = new PNG(file);
	        return png.decode(function(pixels) {
	          return fn(pixels);
	        });
	      });
	    };

	    PNG.load = function(path) {
	      var file;
	      file = fs.readFileSync(path);
	      return new PNG(file);
	    };

	    function PNG(data) {
	      var chunkSize, colors, i, index, key, section, short, text, _i, _j, _ref;
	      this.data = data;
	      this.pos = 8;
	      this.palette = [];
	      this.imgData = [];
	      this.transparency = {};
	      this.text = {};
	      while (true) {
	        chunkSize = this.readUInt32();
	        section = ((function() {
	          var _i, _results;
	          _results = [];
	          for (i = _i = 0; _i < 4; i = ++_i) {
	            _results.push(String.fromCharCode(this.data[this.pos++]));
	          }
	          return _results;
	        }).call(this)).join('');
	        switch (section) {
	          case 'IHDR':
	            this.width = this.readUInt32();
	            this.height = this.readUInt32();
	            this.bits = this.data[this.pos++];
	            this.colorType = this.data[this.pos++];
	            this.compressionMethod = this.data[this.pos++];
	            this.filterMethod = this.data[this.pos++];
	            this.interlaceMethod = this.data[this.pos++];
	            break;
	          case 'PLTE':
	            this.palette = this.read(chunkSize);
	            break;
	          case 'IDAT':
	            for (i = _i = 0; _i < chunkSize; i = _i += 1) {
	              this.imgData.push(this.data[this.pos++]);
	            }
	            break;
	          case 'tRNS':
	            this.transparency = {};
	            switch (this.colorType) {
	              case 3:
	                this.transparency.indexed = this.read(chunkSize);
	                short = 255 - this.transparency.indexed.length;
	                if (short > 0) {
	                  for (i = _j = 0; 0 <= short ? _j < short : _j > short; i = 0 <= short ? ++_j : --_j) {
	                    this.transparency.indexed.push(255);
	                  }
	                }
	                break;
	              case 0:
	                this.transparency.grayscale = this.read(chunkSize)[0];
	                break;
	              case 2:
	                this.transparency.rgb = this.read(chunkSize);
	            }
	            break;
	          case 'tEXt':
	            text = this.read(chunkSize);
	            index = text.indexOf(0);
	            key = String.fromCharCode.apply(String, text.slice(0, index));
	            this.text[key] = String.fromCharCode.apply(String, text.slice(index + 1));
	            break;
	          case 'IEND':
	            this.colors = (function() {
	              switch (this.colorType) {
	                case 0:
	                case 3:
	                case 4:
	                  return 1;
	                case 2:
	                case 6:
	                  return 3;
	              }
	            }).call(this);
	            this.hasAlphaChannel = (_ref = this.colorType) === 4 || _ref === 6;
	            colors = this.colors + (this.hasAlphaChannel ? 1 : 0);
	            this.pixelBitlength = this.bits * colors;
	            this.colorSpace = (function() {
	              switch (this.colors) {
	                case 1:
	                  return 'DeviceGray';
	                case 3:
	                  return 'DeviceRGB';
	              }
	            }).call(this);
	            this.imgData = new Buffer(this.imgData);
	            return;
	          default:
	            this.pos += chunkSize;
	        }
	        this.pos += 4;
	        if (this.pos > this.data.length) {
	          throw new Error("Incomplete or corrupt PNG file");
	        }
	      }
	      return;
	    }

	    PNG.prototype.read = function(bytes) {
	      var i, _i, _results;
	      _results = [];
	      for (i = _i = 0; 0 <= bytes ? _i < bytes : _i > bytes; i = 0 <= bytes ? ++_i : --_i) {
	        _results.push(this.data[this.pos++]);
	      }
	      return _results;
	    };

	    PNG.prototype.readUInt32 = function() {
	      var b1, b2, b3, b4;
	      b1 = this.data[this.pos++] << 24;
	      b2 = this.data[this.pos++] << 16;
	      b3 = this.data[this.pos++] << 8;
	      b4 = this.data[this.pos++];
	      return b1 | b2 | b3 | b4;
	    };

	    PNG.prototype.readUInt16 = function() {
	      var b1, b2;
	      b1 = this.data[this.pos++] << 8;
	      b2 = this.data[this.pos++];
	      return b1 | b2;
	    };

	    PNG.prototype.decodePixels = function(fn) {
	      var _this = this;
	      return zlib.inflate(this.imgData, function(err, data) {
	        var byte, c, col, i, left, length, p, pa, paeth, pb, pc, pixelBytes, pixels, pos, row, scanlineLength, upper, upperLeft, _i, _j, _k, _l, _m;
	        if (err) {
	          throw err;
	        }
	        pixelBytes = _this.pixelBitlength / 8;
	        scanlineLength = pixelBytes * _this.width;
	        pixels = new Buffer(scanlineLength * _this.height);
	        length = data.length;
	        row = 0;
	        pos = 0;
	        c = 0;
	        while (pos < length) {
	          switch (data[pos++]) {
	            case 0:
	              for (i = _i = 0; _i < scanlineLength; i = _i += 1) {
	                pixels[c++] = data[pos++];
	              }
	              break;
	            case 1:
	              for (i = _j = 0; _j < scanlineLength; i = _j += 1) {
	                byte = data[pos++];
	                left = i < pixelBytes ? 0 : pixels[c - pixelBytes];
	                pixels[c++] = (byte + left) % 256;
	              }
	              break;
	            case 2:
	              for (i = _k = 0; _k < scanlineLength; i = _k += 1) {
	                byte = data[pos++];
	                col = (i - (i % pixelBytes)) / pixelBytes;
	                upper = row && pixels[(row - 1) * scanlineLength + col * pixelBytes + (i % pixelBytes)];
	                pixels[c++] = (upper + byte) % 256;
	              }
	              break;
	            case 3:
	              for (i = _l = 0; _l < scanlineLength; i = _l += 1) {
	                byte = data[pos++];
	                col = (i - (i % pixelBytes)) / pixelBytes;
	                left = i < pixelBytes ? 0 : pixels[c - pixelBytes];
	                upper = row && pixels[(row - 1) * scanlineLength + col * pixelBytes + (i % pixelBytes)];
	                pixels[c++] = (byte + Math.floor((left + upper) / 2)) % 256;
	              }
	              break;
	            case 4:
	              for (i = _m = 0; _m < scanlineLength; i = _m += 1) {
	                byte = data[pos++];
	                col = (i - (i % pixelBytes)) / pixelBytes;
	                left = i < pixelBytes ? 0 : pixels[c - pixelBytes];
	                if (row === 0) {
	                  upper = upperLeft = 0;
	                } else {
	                  upper = pixels[(row - 1) * scanlineLength + col * pixelBytes + (i % pixelBytes)];
	                  upperLeft = col && pixels[(row - 1) * scanlineLength + (col - 1) * pixelBytes + (i % pixelBytes)];
	                }
	                p = left + upper - upperLeft;
	                pa = Math.abs(p - left);
	                pb = Math.abs(p - upper);
	                pc = Math.abs(p - upperLeft);
	                if (pa <= pb && pa <= pc) {
	                  paeth = left;
	                } else if (pb <= pc) {
	                  paeth = upper;
	                } else {
	                  paeth = upperLeft;
	                }
	                pixels[c++] = (byte + paeth) % 256;
	              }
	              break;
	            default:
	              throw new Error("Invalid filter algorithm: " + data[pos - 1]);
	          }
	          row++;
	        }
	        return fn(pixels);
	      });
	    };

	    PNG.prototype.decodePalette = function() {
	      var c, i, length, palette, pos, ret, transparency, _i, _ref, _ref1;
	      palette = this.palette;
	      transparency = this.transparency.indexed || [];
	      ret = new Buffer(transparency.length + palette.length);
	      pos = 0;
	      length = palette.length;
	      c = 0;
	      for (i = _i = 0, _ref = palette.length; _i < _ref; i = _i += 3) {
	        ret[pos++] = palette[i];
	        ret[pos++] = palette[i + 1];
	        ret[pos++] = palette[i + 2];
	        ret[pos++] = (_ref1 = transparency[c++]) != null ? _ref1 : 255;
	      }
	      return ret;
	    };

	    PNG.prototype.copyToImageData = function(imageData, pixels) {
	      var alpha, colors, data, i, input, j, k, length, palette, v, _ref;
	      colors = this.colors;
	      palette = null;
	      alpha = this.hasAlphaChannel;
	      if (this.palette.length) {
	        palette = (_ref = this._decodedPalette) != null ? _ref : this._decodedPalette = this.decodePalette();
	        colors = 4;
	        alpha = true;
	      }
	      data = (imageData != null ? imageData.data : void 0) || imageData;
	      length = data.length;
	      input = palette || pixels;
	      i = j = 0;
	      if (colors === 1) {
	        while (i < length) {
	          k = palette ? pixels[i / 4] * 4 : j;
	          v = input[k++];
	          data[i++] = v;
	          data[i++] = v;
	          data[i++] = v;
	          data[i++] = alpha ? input[k++] : 255;
	          j = k;
	        }
	      } else {
	        while (i < length) {
	          k = palette ? pixels[i / 4] * 4 : j;
	          data[i++] = input[k++];
	          data[i++] = input[k++];
	          data[i++] = input[k++];
	          data[i++] = alpha ? input[k++] : 255;
	          j = k;
	        }
	      }
	    };

	    PNG.prototype.decode = function(fn) {
	      var ret,
	        _this = this;
	      ret = new Buffer(this.width * this.height * 4);
	      return this.decodePixels(function(pixels) {
	        _this.copyToImageData(ret, pixels);
	        return fn(ret);
	      });
	    };

	    return PNG;

	  })();

	}).call(this);

	/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(4).Buffer))

/***/ },
/* 52 */
/***/ function(module, exports, __webpack_require__) {

	/* WEBPACK VAR INJECTION */(function(Buffer, __dirname) {// Generated by CoffeeScript 1.7.1

	/*
	PDFFont - embeds fonts in PDF documents
	By Devon Govett
	 */

	(function() {
	  var AFMFont, PDFFont, Subset, TTFFont, fs;

	  TTFFont = __webpack_require__(64);

	  AFMFont = __webpack_require__(63);

	  Subset = __webpack_require__(65);

	  fs = __webpack_require__(10);

	  PDFFont = (function() {
	    var STANDARD_FONTS, toUnicodeCmap;

	    function PDFFont(document, src, family, id) {
	      this.document = document;
	      this.id = id;
	      if (typeof src === 'string') {
	        if (src in STANDARD_FONTS) {
	          this.isAFM = true;
	          this.font = new AFMFont(STANDARD_FONTS[src]());
	          this.registerAFM(src);
	          return;
	        } else if (/\.(ttf|ttc)$/i.test(src)) {
	          this.font = TTFFont.open(src, family);
	        } else if (/\.dfont$/i.test(src)) {
	          this.font = TTFFont.fromDFont(src, family);
	        } else {
	          throw new Error('Not a supported font format or standard PDF font.');
	        }
	      } else if (Buffer.isBuffer(src)) {
	        this.font = TTFFont.fromBuffer(src, family);
	      } else if (src instanceof Uint8Array) {
	        this.font = TTFFont.fromBuffer(new Buffer(src), family);
	      } else if (src instanceof ArrayBuffer) {
	        this.font = TTFFont.fromBuffer(new Buffer(new Uint8Array(src)), family);
	      } else {
	        throw new Error('Not a supported font format or standard PDF font.');
	      }
	      this.subset = new Subset(this.font);
	      this.registerTTF();
	    }

	    STANDARD_FONTS = {
	      "Courier": function() {
	        return fs.readFileSync(__dirname + "/font/data/Courier.afm", 'utf8');
	      },
	      "Courier-Bold": function() {
	        return fs.readFileSync(__dirname + "/font/data/Courier-Bold.afm", 'utf8');
	      },
	      "Courier-Oblique": function() {
	        return fs.readFileSync(__dirname + "/font/data/Courier-Oblique.afm", 'utf8');
	      },
	      "Courier-BoldOblique": function() {
	        return fs.readFileSync(__dirname + "/font/data/Courier-BoldOblique.afm", 'utf8');
	      },
	      "Helvetica": function() {
	        return fs.readFileSync(__dirname + "/font/data/Helvetica.afm", 'utf8');
	      },
	      "Helvetica-Bold": function() {
	        return fs.readFileSync(__dirname + "/font/data/Helvetica-Bold.afm", 'utf8');
	      },
	      "Helvetica-Oblique": function() {
	        return fs.readFileSync(__dirname + "/font/data/Helvetica-Oblique.afm", 'utf8');
	      },
	      "Helvetica-BoldOblique": function() {
	        return fs.readFileSync(__dirname + "/font/data/Helvetica-BoldOblique.afm", 'utf8');
	      },
	      "Times-Roman": function() {
	        return fs.readFileSync(__dirname + "/font/data/Times-Roman.afm", 'utf8');
	      },
	      "Times-Bold": function() {
	        return fs.readFileSync(__dirname + "/font/data/Times-Bold.afm", 'utf8');
	      },
	      "Times-Italic": function() {
	        return fs.readFileSync(__dirname + "/font/data/Times-Italic.afm", 'utf8');
	      },
	      "Times-BoldItalic": function() {
	        return fs.readFileSync(__dirname + "/font/data/Times-BoldItalic.afm", 'utf8');
	      },
	      "Symbol": function() {
	        return fs.readFileSync(__dirname + "/font/data/Symbol.afm", 'utf8');
	      },
	      "ZapfDingbats": function() {
	        return fs.readFileSync(__dirname + "/font/data/ZapfDingbats.afm", 'utf8');
	      }
	    };

	    PDFFont.prototype.use = function(characters) {
	      var _ref;
	      return (_ref = this.subset) != null ? _ref.use(characters) : void 0;
	    };

	    PDFFont.prototype.embed = function() {
	      if (this.embedded || (this.dictionary == null)) {
	        return;
	      }
	      if (this.isAFM) {
	        this.embedAFM();
	      } else {
	        this.embedTTF();
	      }
	      return this.embedded = true;
	    };

	    PDFFont.prototype.encode = function(text) {
	      var _ref;
	      if (this.isAFM) {
	        return this.font.encodeText(text);
	      } else {
	        return ((_ref = this.subset) != null ? _ref.encodeText(text) : void 0) || text;
	      }
	    };

	    PDFFont.prototype.ref = function() {
	      return this.dictionary != null ? this.dictionary : this.dictionary = this.document.ref();
	    };

	    PDFFont.prototype.registerTTF = function() {
	      var e, hi, low, raw, _ref;
	      this.name = this.font.name.postscriptName;
	      this.scaleFactor = 1000.0 / this.font.head.unitsPerEm;
	      this.bbox = (function() {
	        var _i, _len, _ref, _results;
	        _ref = this.font.bbox;
	        _results = [];
	        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
	          e = _ref[_i];
	          _results.push(Math.round(e * this.scaleFactor));
	        }
	        return _results;
	      }).call(this);
	      this.stemV = 0;
	      if (this.font.post.exists) {
	        raw = this.font.post.italic_angle;
	        hi = raw >> 16;
	        low = raw & 0xFF;
	        if (hi & 0x8000 !== 0) {
	          hi = -((hi ^ 0xFFFF) + 1);
	        }
	        this.italicAngle = +("" + hi + "." + low);
	      } else {
	        this.italicAngle = 0;
	      }
	      this.ascender = Math.round(this.font.ascender * this.scaleFactor);
	      this.decender = Math.round(this.font.decender * this.scaleFactor);
	      this.lineGap = Math.round(this.font.lineGap * this.scaleFactor);
	      this.capHeight = (this.font.os2.exists && this.font.os2.capHeight) || this.ascender;
	      this.xHeight = (this.font.os2.exists && this.font.os2.xHeight) || 0;
	      this.familyClass = (this.font.os2.exists && this.font.os2.familyClass || 0) >> 8;
	      this.isSerif = (_ref = this.familyClass) === 1 || _ref === 2 || _ref === 3 || _ref === 4 || _ref === 5 || _ref === 7;
	      this.isScript = this.familyClass === 10;
	      this.flags = 0;
	      if (this.font.post.isFixedPitch) {
	        this.flags |= 1 << 0;
	      }
	      if (this.isSerif) {
	        this.flags |= 1 << 1;
	      }
	      if (this.isScript) {
	        this.flags |= 1 << 3;
	      }
	      if (this.italicAngle !== 0) {
	        this.flags |= 1 << 6;
	      }
	      this.flags |= 1 << 5;
	      if (!this.font.cmap.unicode) {
	        throw new Error('No unicode cmap for font');
	      }
	    };

	    PDFFont.prototype.embedTTF = function() {
	      var charWidths, cmap, code, data, descriptor, firstChar, fontfile, glyph;
	      data = this.subset.encode();
	      fontfile = this.document.ref();
	      fontfile.write(data);
	      fontfile.data.Length1 = fontfile.uncompressedLength;
	      fontfile.end();
	      descriptor = this.document.ref({
	        Type: 'FontDescriptor',
	        FontName: this.subset.postscriptName,
	        FontFile2: fontfile,
	        FontBBox: this.bbox,
	        Flags: this.flags,
	        StemV: this.stemV,
	        ItalicAngle: this.italicAngle,
	        Ascent: this.ascender,
	        Descent: this.decender,
	        CapHeight: this.capHeight,
	        XHeight: this.xHeight
	      });
	      descriptor.end();
	      firstChar = +Object.keys(this.subset.cmap)[0];
	      charWidths = (function() {
	        var _ref, _results;
	        _ref = this.subset.cmap;
	        _results = [];
	        for (code in _ref) {
	          glyph = _ref[code];
	          _results.push(Math.round(this.font.widthOfGlyph(glyph)));
	        }
	        return _results;
	      }).call(this);
	      cmap = this.document.ref();
	      cmap.end(toUnicodeCmap(this.subset.subset));
	      this.dictionary.data = {
	        Type: 'Font',
	        BaseFont: this.subset.postscriptName,
	        Subtype: 'TrueType',
	        FontDescriptor: descriptor,
	        FirstChar: firstChar,
	        LastChar: firstChar + charWidths.length - 1,
	        Widths: charWidths,
	        Encoding: 'MacRomanEncoding',
	        ToUnicode: cmap
	      };
	      return this.dictionary.end();
	    };

	    toUnicodeCmap = function(map) {
	      var code, codes, range, unicode, unicodeMap, _i, _len;
	      unicodeMap = '/CIDInit /ProcSet findresource begin\n12 dict begin\nbegincmap\n/CIDSystemInfo <<\n  /Registry (Adobe)\n  /Ordering (UCS)\n  /Supplement 0\n>> def\n/CMapName /Adobe-Identity-UCS def\n/CMapType 2 def\n1 begincodespacerange\n<00><ff>\nendcodespacerange';
	      codes = Object.keys(map).sort(function(a, b) {
	        return a - b;
	      });
	      range = [];
	      for (_i = 0, _len = codes.length; _i < _len; _i++) {
	        code = codes[_i];
	        if (range.length >= 100) {
	          unicodeMap += "\n" + range.length + " beginbfchar\n" + (range.join('\n')) + "\nendbfchar";
	          range = [];
	        }
	        unicode = ('0000' + map[code].toString(16)).slice(-4);
	        code = (+code).toString(16);
	        range.push("<" + code + "><" + unicode + ">");
	      }
	      if (range.length) {
	        unicodeMap += "\n" + range.length + " beginbfchar\n" + (range.join('\n')) + "\nendbfchar\n";
	      }
	      return unicodeMap += 'endcmap\nCMapName currentdict /CMap defineresource pop\nend\nend';
	    };

	    PDFFont.prototype.registerAFM = function(name) {
	      var _ref;
	      this.name = name;
	      return _ref = this.font, this.ascender = _ref.ascender, this.decender = _ref.decender, this.bbox = _ref.bbox, this.lineGap = _ref.lineGap, _ref;
	    };

	    PDFFont.prototype.embedAFM = function() {
	      this.dictionary.data = {
	        Type: 'Font',
	        BaseFont: this.name,
	        Subtype: 'Type1',
	        Encoding: 'WinAnsiEncoding'
	      };
	      return this.dictionary.end();
	    };

	    PDFFont.prototype.widthOfString = function(string, size) {
	      var charCode, i, scale, width, _i, _ref;
	      string = '' + string;
	      width = 0;
	      for (i = _i = 0, _ref = string.length; 0 <= _ref ? _i < _ref : _i > _ref; i = 0 <= _ref ? ++_i : --_i) {
	        charCode = string.charCodeAt(i);
	        width += this.font.widthOfGlyph(this.font.characterToGlyph(charCode)) || 0;
	      }
	      scale = size / 1000;
	      return width * scale;
	    };

	    PDFFont.prototype.lineHeight = function(size, includeGap) {
	      var gap;
	      if (includeGap == null) {
	        includeGap = false;
	      }
	      gap = includeGap ? this.lineGap : 0;
	      return (this.ascender + gap - this.decender) / 1000 * size;
	    };

	    return PDFFont;

	  })();

	  module.exports = PDFFont;

	}).call(this);

	/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(4).Buffer, "/"))

/***/ },
/* 53 */
/***/ function(module, exports, __webpack_require__) {

	// http://wiki.commonjs.org/wiki/Unit_Testing/1.0
	//
	// THIS IS NOT TESTED NOR LIKELY TO WORK OUTSIDE V8!
	//
	// Originally from narwhal.js (http://narwhaljs.org)
	// Copyright (c) 2009 Thomas Robinson <280north.com>
	//
	// Permission is hereby granted, free of charge, to any person obtaining a copy
	// of this software and associated documentation files (the 'Software'), to
	// deal in the Software without restriction, including without limitation the
	// rights to use, copy, modify, merge, publish, distribute, sublicense, and/or
	// sell copies of the Software, and to permit persons to whom the Software is
	// furnished to do so, subject to the following conditions:
	//
	// The above copyright notice and this permission notice shall be included in
	// all copies or substantial portions of the Software.
	//
	// THE SOFTWARE IS PROVIDED 'AS IS', WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	// IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	// FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	// AUTHORS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN
	// ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
	// WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

	// when used in node, this will actually load the util module we depend on
	// versus loading the builtin util module as happens otherwise
	// this is a bug in node module loading as far as I am concerned
	var util = __webpack_require__(60);

	var pSlice = Array.prototype.slice;
	var hasOwn = Object.prototype.hasOwnProperty;

	// 1. The assert module provides functions that throw
	// AssertionError's when particular conditions are not met. The
	// assert module must conform to the following interface.

	var assert = module.exports = ok;

	// 2. The AssertionError is defined in assert.
	// new assert.AssertionError({ message: message,
	//                             actual: actual,
	//                             expected: expected })

	assert.AssertionError = function AssertionError(options) {
	  this.name = 'AssertionError';
	  this.actual = options.actual;
	  this.expected = options.expected;
	  this.operator = options.operator;
	  if (options.message) {
	    this.message = options.message;
	    this.generatedMessage = false;
	  } else {
	    this.message = getMessage(this);
	    this.generatedMessage = true;
	  }
	  var stackStartFunction = options.stackStartFunction || fail;

	  if (Error.captureStackTrace) {
	    Error.captureStackTrace(this, stackStartFunction);
	  }
	  else {
	    // non v8 browsers so we can have a stacktrace
	    var err = new Error();
	    if (err.stack) {
	      var out = err.stack;

	      // try to strip useless frames
	      var fn_name = stackStartFunction.name;
	      var idx = out.indexOf('\n' + fn_name);
	      if (idx >= 0) {
	        // once we have located the function frame
	        // we need to strip out everything before it (and its line)
	        var next_line = out.indexOf('\n', idx + 1);
	        out = out.substring(next_line + 1);
	      }

	      this.stack = out;
	    }
	  }
	};

	// assert.AssertionError instanceof Error
	util.inherits(assert.AssertionError, Error);

	function replacer(key, value) {
	  if (util.isUndefined(value)) {
	    return '' + value;
	  }
	  if (util.isNumber(value) && !isFinite(value)) {
	    return value.toString();
	  }
	  if (util.isFunction(value) || util.isRegExp(value)) {
	    return value.toString();
	  }
	  return value;
	}

	function truncate(s, n) {
	  if (util.isString(s)) {
	    return s.length < n ? s : s.slice(0, n);
	  } else {
	    return s;
	  }
	}

	function getMessage(self) {
	  return truncate(JSON.stringify(self.actual, replacer), 128) + ' ' +
	         self.operator + ' ' +
	         truncate(JSON.stringify(self.expected, replacer), 128);
	}

	// At present only the three keys mentioned above are used and
	// understood by the spec. Implementations or sub modules can pass
	// other keys to the AssertionError's constructor - they will be
	// ignored.

	// 3. All of the following functions must throw an AssertionError
	// when a corresponding condition is not met, with a message that
	// may be undefined if not provided.  All assertion methods provide
	// both the actual and expected values to the assertion error for
	// display purposes.

	function fail(actual, expected, message, operator, stackStartFunction) {
	  throw new assert.AssertionError({
	    message: message,
	    actual: actual,
	    expected: expected,
	    operator: operator,
	    stackStartFunction: stackStartFunction
	  });
	}

	// EXTENSION! allows for well behaved errors defined elsewhere.
	assert.fail = fail;

	// 4. Pure assertion tests whether a value is truthy, as determined
	// by !!guard.
	// assert.ok(guard, message_opt);
	// This statement is equivalent to assert.equal(true, !!guard,
	// message_opt);. To test strictly for the value true, use
	// assert.strictEqual(true, guard, message_opt);.

	function ok(value, message) {
	  if (!value) fail(value, true, mes