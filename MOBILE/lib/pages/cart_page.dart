import 'package:flutter/material.dart';
import '../services/cart_service.dart';
import '../services/api_service.dart';

class CartPage extends StatefulWidget {
  final Map<String, dynamic> user;

  const CartPage({super.key, required this.user});

  @override
  State<CartPage> createState() => _CartPageState();
}

class _CartPageState extends State<CartPage> {
  final Color primaryColor = const Color(0xFF2563EB);
  final Color darkBlue = const Color(0xFF0F172A);
  final Color bgGray = const Color(0xFFF1F5F9); // Diubah sedikit lebih soft

  bool _isLoading = false; // Tambahan state untuk loading

  // Fungsi untuk menampilkan snackbar modern
  void _showModernSnackBar(String message, {bool isError = false}) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text(
          message,
          style: const TextStyle(fontWeight: FontWeight.w500),
        ),
        backgroundColor: isError ? Colors.redAccent : darkBlue,
        behavior: SnackBarBehavior.floating,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
        margin: const EdgeInsets.all(16),
        elevation: 0,
      ),
    );
  }

  Future<void> submitBorrowing() async {
    if (CartService.getBooks(widget.user['id']).isEmpty) {
      _showModernSnackBar("Keranjang masih kosong!", isError: true);
      return;
    }

    setState(() => _isLoading = true);

    try {
      Map<String, List<int>> grouped = {};

      for (var book in CartService.getBooks(widget.user['id'])) {
        String type = book['loan_type'];
        grouped.putIfAbsent(type, () => []);
        grouped[type]!.add(book['id']);
      }

      for (var entry in grouped.entries) {
        final result = await ApiService.borrowBooks(
          userId: widget.user['id'],
          bookIds: entry.value,
          loanType: entry.key,
        );

        if (result['success'] == false) {
          _showModernSnackBar(result['message'], isError: true);

          return;
        }
      }

      if (!mounted) return;

      _showModernSnackBar("🎉 Peminjaman berhasil diajukan");
      CartService.clear(widget.user['id']);
      Navigator.pop(context, true);
    } catch (e) {
      if (!mounted) return;
      _showModernSnackBar(e.toString(), isError: true);
    } finally {
      if (mounted) setState(() => _isLoading = false);
    }
  }

  Color getLoanColor(String type) {
    switch (type.toLowerCase()) {
      case 'harian':
        return Colors.teal;
      case 'mingguan':
        return Colors.orange;
      case 'semester':
        return Colors.indigo;
      default:
        return primaryColor;
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: bgGray,
      appBar: AppBar(
        backgroundColor: bgGray,
        elevation: 0,
        scrolledUnderElevation: 0,
        centerTitle: true,
        title: Text(
          "Keranjang Peminjaman",
          style: TextStyle(
            color: darkBlue,
            fontWeight: FontWeight.w800,
            fontSize: 18,
            letterSpacing: 0.5,
          ),
        ),
        iconTheme: IconThemeData(color: darkBlue),
      ),
      body: Column(
        children: [
          // KARTU RINGKASAN MODERN
          Container(
            margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
            padding: const EdgeInsets.all(20),
            decoration: BoxDecoration(
              gradient: const LinearGradient(
                colors: [Color(0xFF3B82F6), Color(0xFF1D4ED8)],
                begin: Alignment.topLeft,
                end: Alignment.bottomRight,
              ),
              borderRadius: BorderRadius.circular(24),
              boxShadow: [
                BoxShadow(
                  color: primaryColor.withOpacity(0.3),
                  blurRadius: 20,
                  offset: const Offset(0, 8),
                ),
              ],
            ),
            child: Row(
              children: [
                Container(
                  padding: const EdgeInsets.all(12),
                  decoration: BoxDecoration(
                    color: Colors.white.withOpacity(0.2),
                    shape: BoxShape.circle,
                  ),
                  child: const Icon(
                    Icons.shopping_cart_checkout_rounded,
                    color: Colors.white,
                    size: 28,
                  ),
                ),
                const SizedBox(width: 16),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        "${CartService.total(widget.user['id'])} Buku Terpilih",
                        style: const TextStyle(
                          color: Colors.white,
                          fontWeight: FontWeight.bold,
                          fontSize: 20,
                        ),
                      ),
                      const SizedBox(height: 4),
                      Text(
                        "Siap diajukan untuk peminjaman",
                        style: TextStyle(
                          color: Colors.white.withOpacity(0.8),
                          fontSize: 13,
                        ),
                      ),
                    ],
                  ),
                ),
              ],
            ),
          ),

          const SizedBox(height: 8),

          // DAFTAR BUKU
          Expanded(
            child: CartService.getBooks(widget.user['id']).isEmpty
                ? _buildEmptyState()
                : ListView.separated(
                    physics: const BouncingScrollPhysics(),
                    padding: const EdgeInsets.symmetric(
                      horizontal: 16,
                      vertical: 8,
                    ),
                    itemCount: CartService.getBooks(widget.user['id']).length,
                    separatorBuilder: (context, index) =>
                        const SizedBox(height: 12),
                    itemBuilder: (context, index) {
                      final book = CartService.getBooks(
                        widget.user['id'],
                      )[index];
                      return _buildBookCard(book);
                    },
                  ),
          ),

          // BOTTOM ACTION BAR
          Container(
            padding: const EdgeInsets.fromLTRB(
              16,
              16,
              16,
              32,
            ), // Padding ekstra di bawah untuk safe area
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: const BorderRadius.vertical(
                top: Radius.circular(32),
              ),
              boxShadow: [
                BoxShadow(
                  color: Colors.black.withOpacity(0.05),
                  blurRadius: 20,
                  offset: const Offset(0, -5),
                ),
              ],
            ),
            child: SizedBox(
              width: double.infinity,
              height: 56,
              child: ElevatedButton(
                style: ElevatedButton.styleFrom(
                  backgroundColor: primaryColor,
                  elevation: 0,
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(16),
                  ),
                ),
                onPressed: _isLoading ? null : submitBorrowing,
                child: _isLoading
                    ? const SizedBox(
                        height: 24,
                        width: 24,
                        child: CircularProgressIndicator(
                          color: Colors.white,
                          strokeWidth: 3,
                        ),
                      )
                    : const Text(
                        "Ajukan Peminjaman",
                        style: TextStyle(
                          fontSize: 16,
                          fontWeight: FontWeight.bold,
                          letterSpacing: 0.5,
                          color: Colors.white,
                        ),
                      ),
              ),
            ),
          ),
        ],
      ),
    );
  }

  // WIDGET: Kondisi Keranjang Kosong
  Widget _buildEmptyState() {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Container(
            padding: const EdgeInsets.all(24),
            decoration: BoxDecoration(
              color: primaryColor.withOpacity(0.05),
              shape: BoxShape.circle,
            ),
            child: Icon(
              Icons.menu_book_rounded,
              size: 64,
              color: primaryColor.withOpacity(0.4),
            ),
          ),
          const SizedBox(height: 20),
          Text(
            "Keranjangmu Kosong",
            style: TextStyle(
              color: darkBlue,
              fontWeight: FontWeight.bold,
              fontSize: 18,
            ),
          ),
          const SizedBox(height: 8),
          Text(
            "Pilih buku dulu yuk di perpustakaan!",
            style: TextStyle(color: Colors.grey[600], fontSize: 14),
          ),
        ],
      ),
    );
  }

  // WIDGET: Kartu Buku
  // ... (Bagian atas kode tetap sama)

  // WIDGET: Kartu Buku (Diperbarui dengan Cover)
  Widget _buildBookCard(Map<String, dynamic> book) {
    final loanColor = getLoanColor(book['loan_type']);
    // Asumsi URL storage ada di konfigurasi atau path umum
    final String imageUrl =
        "http://sispussman3padang.my.id/storage/${book['cover'] ?? ''}";

    return Container(
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(20),
        border: Border.all(color: bgGray.withOpacity(0.5)),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.03),
            blurRadius: 10,
            offset: const Offset(0, 4),
          ),
        ],
      ),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // COVER BUKU
          ClipRRect(
            borderRadius: BorderRadius.circular(12),
            child: Image.network(
              imageUrl,
              width: 65,
              height: 90,
              fit: BoxFit.cover,
              errorBuilder: (context, error, stackTrace) {
                return Container(
                  width: 65,
                  height: 90,
                  color: bgGray,
                  child: Icon(
                    Icons.book_rounded,
                    color: primaryColor.withOpacity(0.3),
                  ),
                );
              },
            ),
          ),
          const SizedBox(width: 16),

          // INFORMASI BUKU
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  book['title'],
                  maxLines: 2,
                  overflow: TextOverflow.ellipsis,
                  style: TextStyle(
                    fontWeight: FontWeight.bold,
                    color: darkBlue,
                    fontSize: 15,
                  ),
                ),
                const SizedBox(height: 4),
                Text(
                  book['author'] ?? 'Penulis tidak diketahui',
                  style: TextStyle(color: Colors.grey[600], fontSize: 12),
                ),
                const SizedBox(height: 2),
                Text(
                  "${book['publisher'] ?? 'Penerbit'} • ${book['publication_year'] ?? '-'}",
                  style: TextStyle(color: Colors.grey[500], fontSize: 11),
                ),
                const SizedBox(height: 8),

                // CHIP TIPE PEMINJAMAN
                Container(
                  padding: const EdgeInsets.symmetric(
                    horizontal: 10,
                    vertical: 4,
                  ),
                  decoration: BoxDecoration(
                    color: loanColor.withOpacity(0.1),
                    borderRadius: BorderRadius.circular(8),
                  ),
                  child: Text(
                    book['loan_type'].toString().toUpperCase(),
                    style: TextStyle(
                      color: loanColor,
                      fontSize: 10,
                      fontWeight: FontWeight.w800,
                      letterSpacing: 0.5,
                    ),
                  ),
                ),
              ],
            ),
          ),

          // TOMBOL HAPUS
          IconButton(
            icon: const Icon(
              Icons.delete_outline_rounded,
              color: Colors.redAccent,
            ),
            onPressed: () {
              setState(() {
                CartService.removeBook(
                  widget.user['id'],
                  book['id'],
                  book['loan_type'],
                );
              });
              _showModernSnackBar("Buku dihapus dari keranjang");
            },
          ),
        ],
      ),
    );
  }

  // ... (Sisa kode tetap sama)
}
