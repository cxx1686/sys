<form action="?mod=edit_pass&act=edit_pass" method="post">
	<table class="form">
		<caption>修改密码</caption>
		<tr>
			<th>会员名</th>
			<td><?=$index->member_info['username']?></td>
		</tr>
		<tr>
			<th>原密码</th>
			<td><input type="password" name="oldpass" class="text" /></td>
		</tr>
		<tr>
			<th>新密码</th>
			<td><input type="password" name="newpass" class="text" /></td>
		</tr>
		<tr>
			<th>重复新密码</th>
			<td><input type="password" name="newpass2" class="text" /></td>
		</tr>
		<tr>
			<th></th>
			<td><input type="submit" value="修改" class="btn" /></td>
		</tr>
	</table>
</form>