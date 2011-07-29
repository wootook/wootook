<center>

    <h2><?php echo $this->getData('Declaration')?></h2>
       <table>
         <tr>
            <td><?php echo $this->getData('DeclarationText')?></td>
         </tr>
         <tr>
		    <td><form method="post" action="multi.php?mode=add">
			<textarea name="texte" id="texte"></textarea>
			</td>
		 </tr>
       </table>
       	<input type="submit" value="Envoyer" />
		</form>



</center>