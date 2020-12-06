function includeCkEditor(identifier,lang){
	ClassicEditor.create( document.querySelector( identifier ), {	
	toolbar: {
		items: [
			'heading',
			'|',
			'bold',
			'italic',
			'link',
			'bulletedList',
			'numberedList',
			'|',
			'indent',
			'outdent',
			'|',
			'imageInsert',
			'imageUpload',
			'blockQuote',
			'insertTable',
			'mediaEmbed',
			'codeBlock',
			'undo',
			'redo'
		]
	},
	language: lang,
	image: {
		toolbar: [
			'imageTextAlternative',
			'imageStyle:full',
			'imageStyle:side'
		]
	},
	table: {
		contentToolbar: [
			'tableColumn',
			'tableRow',
			'mergeTableCells'
		]
	},
	licenseKey: '',
	
} )
.then( editor => {
	window.editor = editor;
} )
.catch( error => {
	console.error( 'Oops, something went wrong!' );
	console.error( 'Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:' );
	console.warn( 'Build id: w6otliccyxof-287cihodwhfo' );
	console.error( error );
} );
}