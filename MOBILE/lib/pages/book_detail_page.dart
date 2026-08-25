import 'package:flutter/material.dart';
import '../services/api_service.dart';
import '../services/cart_service.dart';

class BookDetailPage extends StatefulWidget {
  final Map<String, dynamic> book;
  final Map<String, dynamic> user;

  const BookDetailPage({super.key, required this.book, required this.user});

  @override
  State<BookDetailPage> createState() => _BookDetailPageState();
}

class _BookDetailPageState extends State<BookDetailPage> {
  String selectedLoanType = 'harian';

  // Design Tokens / Colors (Disamakan dengan standar Guru)
  final Color primaryColor = const Color(0xFF2563EB); // Royal Blue
  final Color darkBlue = const Color(0xFF0F172A); // Slate 900
  final Color secondaryText = const Color(0xFF64748B); // Slate 500
  final Color bgGray = const Color(0xFFF8FAFC); // Slate 50

  @override
  Widget build(BuildContext context) {
    final book = widget.book;

    return Scaffold(
      backgroundColor: bgGray,
      appBar: AppBar(
        backgroundColor: Colors.transparent,
        elevation: 0,
        leading: Padding(
          padding: const EdgeInsets.only(left: 12.0, top: 8.0),
          child: CircleAvatar(
            backgroundColor: Colors.white.withOpacity(0.9),
            child: IconButton(
              icon: Icon(
                Icons.arrow_back_ios_new_rounded,
                color: darkBlue,
                size: 18,
              ),
              onPressed: () => Navigator.pop(context),
            ),
          ),
        ),
      ),
      // Menggunakan struktur flat SafeArea & Stack seperti halaman Guru asli
      body: SafeArea(
        top: false,
        child: SingleChildScrollView(
          physics: const BouncingScrollPhysics(),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // --- HERO BG IMAGE & COVER STACK (Mengikuti Struktur Guru) ---
              Stack(
                alignment: Alignment.center,
                children: [
                  Container(
                    height: 280,
                    width: double.infinity,
                    decoration: BoxDecoration(
                      gradient: LinearGradient(
                        colors: [darkBlue, const Color(0xFF1E3A8A)],
                        begin: Alignment.topCenter,
                        end: Alignment.bottomCenter,
                      ),
                    ),
                    child: Opacity(
                      opacity: 0.15,
                      child: Image.network(
                        "http://sispussman3padang.my.id/storage/${book['cover']}",
                        fit: BoxFit.cover,
                        errorBuilder: (_, __, ___) => const SizedBox(),
                      ),
                    ),
                  ),
                  Positioned(
                    bottom: 20,
                    child: Container(
                      height: 190,
                      width: 130,
                      decoration: BoxDecoration(
                        borderRadius: BorderRadius.circular(12),
                        boxShadow: [
                          BoxShadow(
                            color: Colors.black.withOpacity(0.3),
                            blurRadius: 20,
                            offset: const Offset(0, 8),
                          ),
                        ],
                      ),
                      child: ClipRRect(
                        borderRadius: BorderRadius.circular(12),
                        child: Image.network(
                          "http://sispussman3padang.my.id/storage/${book['cover']}",
                          fit: BoxFit.cover,
                          errorBuilder: (context, error, stackTrace) {
                            return Container(
                              color: Colors.white,
                              child: Icon(
                                Icons.menu_book_rounded,
                                size: 50,
                                color: secondaryText,
                              ),
                            );
                          },
                        ),
                      ),
                    ),
                  ),
                ],
              ),

              // --- KONTEN UTAMA (Tanpa efek sliver/translate negatif) ---
              Padding(
                padding: const EdgeInsets.all(24.0),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    // Kategori Badges
                    Center(
                      child: Container(
                        padding: const EdgeInsets.symmetric(
                          horizontal: 14,
                          vertical: 6,
                        ),
                        decoration: BoxDecoration(
                          color: primaryColor.withOpacity(0.1),
                          borderRadius: BorderRadius.circular(30),
                        ),
                        child: Text(
                          book['category']?['name']?.toUpperCase() ?? 'UMUM',
                          style: TextStyle(
                            color: primaryColor,
                            fontWeight: FontWeight.bold,
                            fontSize: 11,
                            letterSpacing: 0.8,
                          ),
                        ),
                      ),
                    ),
                    const SizedBox(height: 16),

                    // Judul Buku & Penulis
                    Center(
                      child: Text(
                        book['title'] ?? '-',
                        textAlign: TextAlign.center,
                        style: TextStyle(
                          fontSize: 22,
                          fontWeight: FontWeight.w800,
                          color: darkBlue,
                          height: 1.3,
                        ),
                      ),
                    ),
                    const SizedBox(height: 6),
                    Center(
                      child: Text(
                        book['author'] ?? '-',
                        style: TextStyle(
                          fontSize: 14,
                          color: secondaryText,
                          fontWeight: FontWeight.w500,
                        ),
                      ),
                    ),
                    const SizedBox(height: 24),

                    // --- STATS ROW (Disesuaikan dengan versi Guru) ---
                    Row(
                      children: [
                        Expanded(
                          child: _statCard(
                            Icons.inventory_2_rounded,
                            "${book['stock']}",
                            "Stok Tersedia",
                          ),
                        ),
                        const SizedBox(width: 12),
                        Expanded(
                          child: _statCard(
                            Icons.bookmark_added_rounded,
                            "${book['dipinjam'] ?? 0}",
                            "Dipinjam",
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 28),

                    // --- DESKRIPSI ---
                    Text(
                      "Deskripsi Buku",
                      style: TextStyle(
                        fontSize: 16,
                        fontWeight: FontWeight.bold,
                        color: darkBlue,
                      ),
                    ),
                    const SizedBox(height: 8),
                    Text(
                      book['description'] ??
                          'Tidak ada deskripsi info mengenai buku ini.',
                      style: TextStyle(
                        height: 1.6,
                        color: const Color(0xFF475569),
                        fontSize: 14,
                      ),
                    ),
                    const SizedBox(height: 24),

                    // --- INFORMASI DETAIL (Sekarang ada di halaman Siswa) ---
                    Text(
                      "Informasi Detail",
                      style: TextStyle(
                        fontSize: 16,
                        fontWeight: FontWeight.bold,
                        color: darkBlue,
                      ),
                    ),
                    const SizedBox(height: 10),
                    Container(
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(16),
                        border: Border.all(color: const Color(0xFFE2E8F0)),
                      ),
                      child: Column(
                        children: [
                          _buildSpecListTile(
                            Icons.assignment_ind_rounded,
                            "Penulis",
                            book['author'],
                          ),
                          _buildDivider(),
                          _buildSpecListTile(
                            Icons.business_rounded,
                            "Penerbit",
                            book['publisher'],
                          ),
                          _buildDivider(),
                          _buildSpecListTile(
                            Icons.calendar_today_rounded,
                            "Tahun Terbit",
                            "${book['publication_year'] ?? '-'}",
                          ),
                        ],
                      ),
                    ),
                    const SizedBox(height: 24),

                    // --- PILIHAN DURASI PINJAM ---
                    Text(
                      "Pilih Opsi Durasi Pinjam",
                      style: TextStyle(
                        fontSize: 15,
                        fontWeight: FontWeight.bold,
                        color: darkBlue,
                      ),
                    ),
                    const SizedBox(height: 8),
                    _buildDropdown(),

                    const SizedBox(height: 100), // Ganjalan scroll bottomSheet
                  ],
                ),
              ),
            ],
          ),
        ),
      ),

      // --- STICKY FOOTER ACTION BUTTON (1 Tombol versi Siswa) ---
      bottomSheet: Container(
        padding: const EdgeInsets.fromLTRB(24, 16, 24, 24),
        decoration: BoxDecoration(
          color: Colors.white,
          boxShadow: [
            BoxShadow(
              color: Colors.black.withOpacity(0.04),
              blurRadius: 12,
              offset: const Offset(0, -4),
            ),
          ],
          border: const Border(
            top: BorderSide(color: Color(0xFFF1F5F9), width: 1.5),
          ),
        ),
        child: SizedBox(
          width: double.infinity,
          height: 52,
          child: ElevatedButton(
            style: ElevatedButton.styleFrom(
              backgroundColor: primaryColor,
              foregroundColor: Colors.white,
              elevation: 0,
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(14),
              ),
            ),

            onPressed: () {
            CartService.addBook(
    userId: widget.user['id'],
    book: book,
    loanType: selectedLoanType,
);

              ScaffoldMessenger.of(context).showSnackBar(
                SnackBar(
                  content: Text("${book['title']} ditambahkan ke keranjang"),
                ),
              );
            },

            child: const Text(
              "Tambah ke Keranjang",
              style: TextStyle(fontSize: 15, fontWeight: FontWeight.bold),
            ),
          ),
        ),
      ),
    );
  }

  // --- REUSABLE DROPDOWN WIDGET ---
  Widget _buildDropdown() {
    return DropdownButtonFormField<String>(
      value: selectedLoanType,
      dropdownColor: Colors.white,
      style: TextStyle(
        color: darkBlue,
        fontSize: 15,
        fontWeight: FontWeight.w500,
      ),
      decoration: InputDecoration(
        filled: true,
        fillColor: Colors.white,
        prefixIcon: Icon(Icons.history_toggle_off_rounded, color: primaryColor),
        contentPadding: const EdgeInsets.symmetric(
          horizontal: 16,
          vertical: 14,
        ),
        enabledBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(14),
          borderSide: const BorderSide(color: Color(0xFFE2E8F0)),
        ),
        focusedBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(14),
          borderSide: BorderSide(color: primaryColor, width: 1.5),
        ),
      ),
      items: const [
        DropdownMenuItem(value: 'harian', child: Text('Harian — Max 3 Hari')),
        DropdownMenuItem(
          value: 'mingguan',
          child: Text('Mingguan — Max 7 Hari'),
        ),
        DropdownMenuItem(
          value: 'semester',
          child: Text('Semester — Max 120 Hari'),
        ),
      ],
      onChanged: (v) => setState(() => selectedLoanType = v!),
    );
  }

  // --- WIDGET HELPER METHODS ---
  Widget _statCard(IconData icon, String value, String label) {
    return Container(
      padding: const EdgeInsets.symmetric(vertical: 16, horizontal: 8),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: const Color(0xFFE2E8F0)),
      ),
      child: Column(
        children: [
          CircleAvatar(
            radius: 18,
            backgroundColor: const Color(0xFFEFF6FF),
            child: Icon(icon, color: primaryColor, size: 18),
          ),
          const SizedBox(height: 10),
          Text(
            value,
            style: TextStyle(
              fontSize: 18,
              fontWeight: FontWeight.w800,
              color: darkBlue,
            ),
          ),
          const SizedBox(height: 4),
          Text(
            label,
            style: TextStyle(
              fontSize: 11,
              color: secondaryText,
              fontWeight: FontWeight.w500,
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildSpecListTile(IconData icon, String title, String? trailing) {
    return ListTile(
      minLeadingWidth: 10,
      leading: Icon(icon, color: primaryColor, size: 20),
      title: Text(title, style: TextStyle(fontSize: 13, color: secondaryText)),
      trailing: Text(
        trailing ?? '-',
        style: TextStyle(
          fontSize: 14,
          fontWeight: FontWeight.w600,
          color: darkBlue,
        ),
      ),
    );
  }

  Widget _buildDivider() => const Divider(
    height: 1,
    thickness: 1,
    indent: 50,
    color: Color(0xFFF1F5F9),
  );

  // --- BORROW LOGIC EXECUTION ---
  Future<void> _handleBorrow(Map<String, dynamic> book) async {
    try {
      final result = await ApiService.borrowBook(
        userId: widget.user['id'],
        bookId: book['id'],
        loanType: selectedLoanType,
      );

      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text(result['message'] ?? 'Berhasil diajukan')),
      );
      Navigator.pop(context, true);
    } catch (e) {
      ScaffoldMessenger.of(
        context,
      ).showSnackBar(SnackBar(content: Text(e.toString())));
    }
  }
}
