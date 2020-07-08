		<br><br>
        Your Admin account<br>
        <input type="text" name="admin" required><br>
        Admin Password<br>
        <input type="text" name="password" required>
        <input type="hidden" name="blog_title" value="<?php echo $_POST['blog_title'] ?>">
        <input type="hidden" name="perpage" value="<?php echo $_POST['perpage'] ?>">
        <input type="hidden" name="driver" value="<?php echo $driver; ?>">
		<input type="hidden" name="step" value="2">
        <br><br>
        <input type="submit" value="SUBMIT">
        </fieldset>
    </form>

</body>
</html>
