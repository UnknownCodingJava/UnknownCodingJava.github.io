<!-- Add this toggle button for mobile -->
<div id="menu-toggle" onclick="toggleSidebar()">
    <i class="fa fa-bars"></i>
</div>

<!-- The sidebar -->
<div class="sidebar bg-dark bg-gradient">
    <a href="#home"><i class="fa fa-fw fa-home"></i> Home</a>
    <a href="#calendar"><i class="fa fa-fw fa-wrench"></i> Calendar</a>
    <a href="#clients"><i class="fa fa-fw fa-user"></i> agenda</a>
    <a href="#contact"><i class="fa fa-fw fa-envelope"></i>notes</a>
    <!-- Floating plus icon -->
    <i class="fa fa-plus rounded-circle text-center"></i>
</div>
<script>
    function toggleSidebar() {
        document.querySelector('.sidebar').classList.toggle('open');
    }
</script>