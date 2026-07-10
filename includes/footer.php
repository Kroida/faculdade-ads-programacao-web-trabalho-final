<footer class="footer">
        <div class="container">
            <p>&copy; 2026 UniFood. Todos os direitos reservados.</p>
        </div>
    </footer>

    <script src="src/js/index.js"></script>
    <!-- Envia para o JavaScript se o pedido acabou de ser finalizado. -->
    <script> const pedidoFeito = <?php echo json_encode($pedidoFeito ?? false); ?>; </script>
    <script src="src/js/login.js"></script>
</body>

</html>