<style>
	.border{
		border :1px solid rgba(0,0,0,0.2);
	}
	.modal-container{
		width:100%;
		height:100vh;
		background-color:rgba(0,0,0,0.2); 
		position:fixed;top:0px;left:0px;z-index:1500; 
		display:flex;
		justify-content:center;
		align-items:center;
	}

	.modal-content{
		width:70%;
		height:100%;
		background-color:#fff;
		border :1px solid rgba(0,0,0,0.2);
		position:relative;
	}

	.btn-close-modal{
		width:40px;height:40px;border-radius:50%;
		background-color:rgba(0,0,0,0.2);
		position:absolute;top:0px;right:10px;
	}

	.content{
		width:100%;
		height:100%;
		background-color:white;
		display:flex;flex-direction:column;
		
	}

	.comment-list{
		width:100%; 
		flex-grow:1;
	}

	.comment-add{
		width:100%;
		height:180px; 
		
	}

	.comment-add button{
		padding:10px; 
		border-radius:5px;
	}

	.comment-add button:hover{
		background-color:#fff;
		color:black;
	}

	.comment-list ul{
		list-style:none;

	}

	.comment-list li{
		list-style:none;
		display:flex; 
	}
	.comment-list .img{
		min-width:40px;min-height:40px;
		max-width:40px;max-height:40px;border-radius:50%;
		background-color:rgba(0,0,0,0.2);
	}
</style>

<div class="modal-container col-md-12">
	<div class="modal-content col-md-12">
		<div class="btn-close-modal" onclick=" hideModal() "></div>
		<div class="content col-md-8 px-6 py-3" id="contener_comment">
			<!-- contenu du modal -->
		</div>
	</div>
</div>

