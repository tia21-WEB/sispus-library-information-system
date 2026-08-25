import 'package:flutter/material.dart';
import '../services/api_service.dart';
import '../services/cart_service.dart';

class GuruBookDetailPage extends StatefulWidget {
  final Map<String, dynamic> book;
  final Map<String, dynamic> user;

  const GuruBookDetailPage({super.key, required this.book, required this.user});

  @override
  State<GuruBookDetailPage> createState() => _GuruBookDetailPageState();
}

class _GuruBookDetailPageState extends State<GuruBookDetailPage> {
  String selectedLoanType = 'harian';

  // Design Tokens / Colors
  final Color primaryColor = const Color(0xFF2563EB); // Royal Blue
  final Color darkBlue = const Color(0xFF0F172A); // Slate 900
  final Color secondaryText = const Color(0xFF64748B); // Slate 500
  final Color bgGray = const Color(0xFFF8FAFC); // Slate 50

  @override
  Widget build(BuildContext context) {
    final book = widget.book;

    return Scaffold(
      backgroundColor: bgGray,
      body: SafeArea(
        child: Column(
          children: [
            // --- MAIN SCROLLABLE CONTENT ---
            Expanded(
              child: SingleChildScrollView(
                physics: const BouncingScrollPhysics(),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    // --- HERO BLOCK: COVER HERO BACKGROUND ---
                    Stack(
                      children: [
                        Container(
                          width: double.infinity,
                          height: 280,
                          decoration: BoxDecoration(
                            gradient: LinearGradient(
                              colors: [darkBlue, const Color(0xFF1E3A8A)],
                              begin: Alignment.topLeft,
                              end: Alignment.bottomRight,
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
                        // Back Button
                        Positioned(
                          top: 16,
                          left: 16,
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
                        // Floating Book Cover
                        Positioned(
                          bottom: 0,
                          left: 0,
                          right: 0,
                          child: Center(
                            child: Transform.translate(
                              offset: const Offset(0, 20),
                              child: Container(
                                width: 135,
                                height: 195,
                                decoration: BoxDecoration(
                                  borderRadius: BorderRadius.circular(16),
                                  boxShadow: [
                                    BoxShadow(
                                      color: Colors.black.withOpacity(0.25),
                                      blurRadius: 20,
                                      offset: const Offset(0, 8),
                                    ),
                                  ],
                                ),
                                child: ClipRRect(
                                  borderRadius: BorderRadius.circular(16),
                                  child: Image.network(
                                    "http://sispussman3padang.my.id/storage/${book['cover']}",
                                    fit: BoxFit.cover,
                                    errorBuilder: (context, error, stackTrace) {
                                      return Container(
                                        color: Colors.white,
                                        child: Icon(
                                          Icons.menu_book_rounded,
                                          size: 60,
                                          color: secondaryText,
                                        ),
                                      );
                                    },
                                  ),
                                ),
                              ),
                            ),
                          ),
                        ),
                      ],
                    ),

                    const SizedBox(height: 40),

                    // --- BOOK METADATA & BODY ---
                    Padding(
                      padding: const EdgeInsets.symmetric(horizontal: 24),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          // Category Badge
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
                                book['category']?['name']?.toUpperCase() ??
                                    'UMUM',
                                style: TextStyle(
                                  color: primaryColor,
                                  fontWeight: FontWeight.bold,
                                  fontSize: 11,
                                  letterSpacing: 1.0,
                                ),
                              ),
                            ),
                          ),

                          const SizedBox(height: 16),

                          // Book Title
                          Center(
                            child: Text(
                              book['title'] ?? '-',
                              textAlign: TextAlign.center,
                              style: TextStyle(
                                fontSize: 24,
                                fontWeight: FontWeight.w800,
                                color: darkBlue,
                                height: 1.3,
                              ),
                            ),
                          ),

                          const SizedBox(height: 6),

                          // Author & Publisher Info
                          Center(
                            child: Text(
                              "${book['author'] ?? '-'}  •  ${book['publisher'] ?? '-'}",
                              textAlign: TextAlign.center,
                              style: TextStyle(
                                color: secondaryText,
                                fontSize: 14,
                                fontWeight: FontWeight.w500,
                              ),
                            ),
                          ),

                          const SizedBox(height: 24),

                          // --- STATS GRID ROW ---
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
                              const SizedBox(width: 12),
                              Expanded(
                                child: _statCard(
                                  Icons.av_timer_rounded,
                                  "3 Hari",
                                  "Min. Pinjam",
                                ),
                              ),
                            ],
                          ),

                          const SizedBox(height: 28),

                          // --- DESCRIPTION CARD ---
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

                          // --- DETAILED SPECS SHEET ---
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
                              border: Border.all(
                                color: const Color(0xFFE2E8F0),
                              ),
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
                                  "${book['publication_year']}",
                                ),
                              ],
                            ),
                          ),

                          const SizedBox(height: 24),

                          // --- LOAN TYPE SELECTION DROPDOWN ---
                          Text(
                            "Pilih Opsi Durasi Pinjam",
                            style: TextStyle(
                              fontSize: 15,
                              fontWeight: FontWeight.bold,
                              color: darkBlue,
                            ),
                          ),
                          const SizedBox(height: 8),
                          DropdownButtonFormField<String>(
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
                              prefixIcon: Icon(
                                Icons.history_toggle_off_rounded,
                                color: primaryColor,
                              ),
                              contentPadding: const EdgeInsets.symmetric(
                                horizontal: 16,
                                vertical: 14,
                              ),
                              enabledBorder: OutlineInputBorder(
                                borderRadius: BorderRadius.circular(14),
                                borderSide: const BorderSide(
                                  color: Color(0xFFE2E8F0),
                                ),
                              ),
                              focusedBorder: OutlineInputBorder(
                                borderRadius: BorderRadius.circular(14),
                                borderSide: BorderSide(
                                  color: primaryColor,
                                  width: 1.5,
                                ),
                              ),
                            ),
                            items: const [
                              DropdownMenuItem(
                                value: 'harian',
                                child: Text('Harian — Max 3 Hari'),
                              ),
                              DropdownMenuItem(
                                value: 'mingguan',
                                child: Text('Mingguan — Max 7 Hari'),
                              ),
                              DropdownMenuItem(
                                value: 'semester',
                                child: Text('Semester — Max 120 Hari'),
                              ),
                            ],
                            onChanged: (value) {
                              setState(() {
                                selectedLoanType = value!;
                              });
                            },
                          ),
                          const SizedBox(height: 32),
                        ],
                      ),
                    ),
                  ],
                ),
              ),
            ),

            // --- STICKY FOOTER ACTION BUTTONS ---
            Container(
              padding: const EdgeInsets.fromLTRB(24, 12, 24, 20),
              decoration: BoxDecoration(
                color: Colors.white,
                boxShadow: [
                  BoxShadow(
                    color: Colors.black.withOpacity(0.04),
                    blurRadius: 12,
                    offset: const Offset(0, -4),
                  ),
                ],
              ),
              child: Column(
                mainAxisSize: MainAxisSize.min,
                children: [
                  SizedBox(
                    width: double.infinity,
                    height: 52,
                    child: ElevatedButton(
                      style: ElevatedButton.styleFrom(
                        backgroundColor: darkBlue,
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
                            content: Text(
                              "${book['title']} ditambahkan ke keranjang",
                            ),
                          ),
                        );
                      },

                      child: const Text(
                        "Tambah ke Keranjang",
                        style: TextStyle(
                          fontSize: 15,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                    ),
                  ),
                  const SizedBox(height: 10),
                  SizedBox(
                    width: double.infinity,
                    height: 52,
                    child: OutlinedButton(
                      style: OutlinedButton.styleFrom(
                        foregroundColor: primaryColor,
                        side: BorderSide(color: primaryColor, width: 1.5),
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(14),
                        ),
                      ),
                      onPressed: () {
                        selectedLoanType = 'harian';
                        _showCollectiveDialog();
                      },
                      child: const Text(
                        "Pinjam Kolektif (Kelas)",
                        style: TextStyle(
                          fontSize: 15,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                    ),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
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
  Future<void> _handleIndividualBorrow(Map<String, dynamic> book) async {
    try {
      final result = await ApiService.borrowBook(
        userId: widget.user['id'],
        bookId: book['id'],
        loanType: selectedLoanType,
      );

      if (!context.mounted) return;
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

  // --- DIALOG PEMINJAMAN KOLEKTIF ---
  void _showCollectiveDialog() {
    final kelasController = TextEditingController();
    final jumlahController = TextEditingController();

    showDialog(
      context: context,
      builder: (_) {
        return AlertDialog(
          backgroundColor: Colors.white,
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(20),
          ),
          title: Row(
            children: [
              Icon(Icons.groups_rounded, color: primaryColor, size: 26),
              const SizedBox(width: 10),
              Text(
                "Pinjam Kolektif",
                style: TextStyle(
                  color: darkBlue,
                  fontWeight: FontWeight.bold,
                  fontSize: 18,
                ),
              ),
            ],
          ),
          content: SingleChildScrollView(
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                Container(
                  padding: const EdgeInsets.all(12),
                  decoration: BoxDecoration(
                    color: const Color(0xFFF0FDF4), // Emerald light notice box
                    borderRadius: BorderRadius.circular(12),
                    border: Border.all(color: const Color(0xFFDCFCE7)),
                  ),
                  child: Row(
                    children: [
                      const Icon(
                        Icons.info_outline_rounded,
                        color: Color(0xFF16A34A),
                        size: 20,
                      ),
                      const SizedBox(width: 10),
                      Expanded(
                        child: Text(
                          "Peminjaman ini dikhususkan untuk didistribusikan langsung saat jam kelas.",
                          style: TextStyle(
                            color: const Color(0xFF15803D),
                            fontSize: 12,
                            height: 1.4,
                          ),
                        ),
                      ),
                    ],
                  ),
                ),
                const SizedBox(height: 20),
                TextField(
                  controller: kelasController,
                  decoration: InputDecoration(
                    labelText: "Nama Target Kelas",
                    hintText: "Contoh: XI IPA 2",
                    prefixIcon: const Icon(Icons.school_rounded),
                    border: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(12),
                    ),
                  ),
                ),
                const SizedBox(height: 14),
                TextField(
                  controller: jumlahController,
                  keyboardType: TextInputType.number,
                  decoration: InputDecoration(
                    labelText: "Jumlah Unit Buku",
                    hintText: "Contoh: 32",
                    prefixIcon: const Icon(Icons.layers_rounded),
                    border: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(12),
                    ),
                  ),
                ),
              ],
            ),
          ),
          actionsPadding: const EdgeInsets.fromLTRB(16, 0, 16, 16),
          actions: [
            TextButton(
              onPressed: () => Navigator.pop(context),
              child: Text("Batal", style: TextStyle(color: secondaryText)),
            ),
            ElevatedButton(
              style: ElevatedButton.styleFrom(
                backgroundColor: primaryColor,
                foregroundColor: Colors.white,
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(10),
                ),
              ),
              onPressed: () async {
                final qty = int.tryParse(jumlahController.text) ?? 0;
                if (kelasController.text.trim().isEmpty || qty <= 0) {
                  ScaffoldMessenger.of(context).showSnackBar(
                    const SnackBar(
                      content: Text(
                        "Isian data kelas atau jumlah belum valid!",
                      ),
                    ),
                  );
                  return;
                }

                try {
                  final result = await ApiService.borrowBook(
                    userId: widget.user['id'],
                    bookId: widget.book['id'],
                    loanType: 'harian',
                    isCollective: true,
                    className: kelasController.text,
                    quantity: qty,
                  );

                  if (!context.mounted) return;
                  Navigator.pop(context); // close dialog
                  ScaffoldMessenger.of(
                    context,
                  ).showSnackBar(SnackBar(content: Text(result['message'])));
                  Navigator.pop(context, true); // back to catalog
                } catch (e) {
                  ScaffoldMessenger.of(
                    context,
                  ).showSnackBar(SnackBar(content: Text(e.toString())));
                }
              },
              child: const Text("Ajukan Pinjaman"),
            ),
          ],
        );
      },
    );
  }
}
