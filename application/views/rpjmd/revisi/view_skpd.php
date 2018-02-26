<style type="text/css">
	tr.tr-click:hover{		
		background-color: pink;
	}
	td.td-click{
		cursor: pointer;
	}
</style>
<script type="text/javascript">
	$(document).ready(function(){
		$(document).on("click", ".p_revisi", function(){
			prepare_facebox();
			$.blockUI({				
				css: window._css,				
				overlayCSS: window._ovcss
			});
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("rpjmd/view_p_revisi"); ?>',
				data: {id: $(this).attr('id-s')},
				dataType: "json",
				success: function(msg){
					catch_expired_session2(msg);									
					$.unblockUI();
					$.facebox(msg.msg);
				}
			});
		});		
	});
</script>
<article class="module width_full">
 	<header>
 		<h3>
			Revisi RPJMD
		</h3>
 	</header>
 	<div class="module_content"> 		
		<table class="table-common" style="width: 99%">
			<thead>
				<tr>					
					<th>No.</th>
					<th>SKPD</th>
					<th>Visi</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
			<?php			
				$i=0;
				foreach ($revisi as $row) {
					$i++;
			?>
				<tr class="tr-click">
					<td class="td-click" width="50px"><?php echo $i; ?></td>
					<td class="td-click"><?php echo $row->nama_skpd; ?></td>
					<td class="td-click"><?php echo $row->keterangan; ?></td>
					<td align="center" width="50px">
						<input class="p_revisi" type="button" id-s="<?php echo $row->id; ?>" value="Revisi"/>							
					</td>
				</tr>				
			<?php
				}
			?>
			</tbody>
		</table>		
 	</div>
 	<footer> 		
 	</footer> 	 	
</article>