import 'package:flutter/material.dart';
import 'package:sispus_mobile/pages/return_qr_page.dart';
import '../services/api_service.dart';
import 'package:table_calendar/table_calendar.dart';

class PeminjamanPage extends StatefulWidget {
  final Map<String, dynamic> user;

  const PeminjamanPage({super.key, required this.user});

  @override
  State<PeminjamanPage> createState() => _PeminjamanPageState();
}

class _PeminjamanPageState extends State<PeminjamanPage>
    with SingleTickerProviderStateMixin {
  late TabController tabController;
  List borrowings = [];
  bool isLoading = true;
  DateTime _focusedDay = DateTime.now();
  DateTime? _selectedDay;

  // Variabel Warna Utama untuk Konsistensi Desain Modern
  final Color primaryDark = const Color(0xFF0C2340);
  final Color accentBlue = const Color(0xFF2563EB);
  final Color bgLight = const Color(0xFFF8FAFC);

  @override
  void initState() {
    super.initState();
    tabController = TabController(length: 3, vsync: this);
    loadBorrowings();
  }

  Future<void> loadBorrowings() async {
    try {
      final data = await ApiService.getMyBorrowings(widget.user['id']);
      setState(() {
        borrowings = data;
        isLoading = false;
      });
    } catch (e) {
      setState(() {
        isLoading = false;
      });
    }
  }

  List getPeminjaman() {
    return borrowings.where((item) {
      return item['status'] == 'dipinjam' || item['status'] == 'menunggu';
    }).toList();
  }

  List getPengembalian() {
    return borrowings.where((item) {
      return item['status'] == 'menunggu_pengembalian';
    }).toList();
  }

  List getRiwayat() {
    return borrowings.where((item) {
      return item['status'] == 'dikembalikan' || item['status'] == 'ditolak';
    }).toList();
  }

  Widget _statusBadge(String status) {
    Color bgColor;
    Color textColor;
    String textLabel = status.toUpperCase();

    switch (status) {
      case 'dipinjam':
        bgColor = const Color(0xFFDCFCE7);
        textColor = const Color(0xFF15803D);
        textLabel = "SEDANG DIPINJAM";
        break;
      case 'menunggu':
        bgColor = const Color(0xFFFFEDD5);
        textColor = const Color(0xFFC2410C);
        textLabel = "MENUNGGU VERIFIKASI";
        break;
      case 'menunggu_pengembalian':
        bgColor = const Color(0xFFDBEAFE);
        textColor = const Color(0xFF1D4ED8);
        textLabel = "PROSES KEMBALI";
        break;
      case 'dikembalikan':
        bgColor = const Color(0xFFCCFBF1);
        textColor = const Color(0xFF0F766E);
        textLabel = "DIKEMBALIKAN";
        break;
      default:
        bgColor = const Color(0xFFFEE2E2);
        textColor = const Color(0xFFB91C1C);
        textLabel = "DITOLAK";
    }

    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 5),
      decoration: BoxDecoration(
        color: bgColor,
        borderRadius: BorderRadius.circular(8),
      ),
      child: Text(
        textLabel,
        style: TextStyle(
          color: textColor,
          fontWeight: FontWeight.w700,
          fontSize: 11,
          letterSpacing: 0.3,
        ),
      ),
    );
  }

  Widget _lateStatusBadge(Map<String, dynamic> item) {
    if (item['status'] != 'dikembalikan') return const SizedBox.shrink();

    final bool isLate =
        item['is_late'] == 1 ||
        item['is_late'] == true ||
        item['terlambat'] == 1 ||
        item['terlambat'] == true ||
        item['status_pengembalian'] == 'terlambat' ||
        (item['denda'] != null && item['denda'] > 0);

    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
      decoration: BoxDecoration(
        color: isLate ? const Color(0xFFFEE2E2) : const Color(0xFFDCFCE7),
        borderRadius: BorderRadius.circular(6),
      ),
      child: Text(
        isLate ? "TERLAMBAT" : "TEPAT WAKTU",
        style: TextStyle(
          color: isLate ? const Color(0xFFB91C1C) : const Color(0xFF15803D),
          fontWeight: FontWeight.w800,
          fontSize: 10,
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: bgLight,
      appBar: AppBar(
        backgroundColor: Colors.white,
        elevation: 0,
        scrolledUnderElevation: 0,
        title: Text(
          "Peminjaman Buku",
          style: TextStyle(
            color: primaryDark,
            fontWeight: FontWeight.w800,
            fontSize: 22,
          ),
        ),
        bottom: PreferredSize(
          preferredSize: const Size.fromHeight(54),
          child: Container(
            margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 6),
            padding: const EdgeInsets.all(4),
            decoration: BoxDecoration(
              color: const Color(0xFFF1F5F9),
              borderRadius: BorderRadius.circular(12),
            ),
            child: TabBar(
              controller: tabController,
              labelColor: Colors.white,
              unselectedLabelColor: Colors.grey.shade600,
              indicatorSize: TabBarIndicatorSize.tab,
              dividerColor: Colors.transparent,
              indicator: BoxDecoration(
                color: accentBlue,
                borderRadius: BorderRadius.circular(10),
                boxShadow: [
                  BoxShadow(
                    color: accentBlue.withOpacity(0.2),
                    blurRadius: 6,
                    offset: const Offset(0, 2),
                  ),
                ],
              ),
              tabs: const [
                Tab(
                  child: Text(
                    "Pinjaman",
                    style: TextStyle(fontWeight: FontWeight.w600, fontSize: 13),
                  ),
                ),
                Tab(
                  child: Text(
                    "Kembali",
                    style: TextStyle(fontWeight: FontWeight.w600, fontSize: 13),
                  ),
                ),
                Tab(
                  child: Text(
                    "Riwayat",
                    style: TextStyle(fontWeight: FontWeight.w600, fontSize: 13),
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
      body: isLoading
          ? Center(child: CircularProgressIndicator(color: accentBlue))
          : TabBarView(
              controller: tabController,
              children: [
                _buildPeminjaman(),
                _buildPengembalian(),
                _buildRiwayat(),
              ],
            ),
    );
  }

  void _showBorrowingDetail(Map<String, dynamic> item) {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.white,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
      ),
      builder: (_) {
        return DraggableScrollableSheet(
          initialChildSize: 0.6,
          maxChildSize: 0.85,
          minChildSize: 0.5,
          expand: false,
          builder: (context, scrollController) {
            return Padding(
              padding: const EdgeInsets.symmetric(horizontal: 20),
              child: ListView(
                controller: scrollController,
                children: [
                  const SizedBox(height: 12),
                  Center(
                    child: Container(
                      width: 40,
                      height: 5,
                      decoration: BoxDecoration(
                        color: Colors.grey.shade300,
                        borderRadius: BorderRadius.circular(10),
                      ),
                    ),
                  ),
                  const SizedBox(height: 24),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Text(
                        "Detail Peminjaman",
                        style: TextStyle(
                          fontSize: 18,
                          fontWeight: FontWeight.w800,
                          color: primaryDark,
                        ),
                      ),
                      _statusBadge(item['status']),
                    ],
                  ),
                  const SizedBox(height: 8),
                  Row(
                    children: [
                      Icon(
                        Icons.calendar_today_rounded,
                        size: 14,
                        color: Colors.grey.shade500,
                      ),
                      const SizedBox(width: 6),
                      Text(
                        "Batas Kembali: ${item['return_date']}",
                        style: TextStyle(
                          color: Colors.grey.shade600,
                          fontSize: 13,
                          fontWeight: FontWeight.w500,
                        ),
                      ),
                    ],
                  ),
                  const Padding(
                    padding: EdgeInsets.symmetric(vertical: 16),
                    child: Divider(color: Color(0xFFF1F5F9)),
                  ),
                  ...List.generate(item['details'].length, (i) {
                    final book = item['details'][i]['book'];
                    final exemplar = item['details'][i]['exemplar'];

                    return Container(
                      margin: const EdgeInsets.only(bottom: 14),
                      padding: const EdgeInsets.all(12),
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(16),
                        border: Border.all(color: const Color(0xFFE2E8F0)),
                      ),
                      child: Row(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          _bookCover(book['cover']),
                          const SizedBox(width: 14),
                          Expanded(
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text(
                                  book['title'],
                                  style: TextStyle(
                                    fontWeight: FontWeight.bold,
                                    fontSize: 15,
                                    color: primaryDark,
                                  ),
                                  maxLines: 2,
                                  overflow: TextOverflow.ellipsis,
                                ),
                                const SizedBox(height: 4),
                                Text(
                                  "Penulis: ${book['author']}",
                                  style: TextStyle(
                                    color: Colors.grey.shade600,
                                    fontSize: 12,
                                  ),
                                ),
                                Text(
                                  "Penerbit: ${book['publisher']} (${book['publication_year']})",
                                  style: TextStyle(
                                    color: Colors.grey.shade600,
                                    fontSize: 12,
                                  ),
                                ),
                                const SizedBox(height: 10),
                                Container(
                                  padding: const EdgeInsets.symmetric(
                                    horizontal: 10,
                                    vertical: 4,
                                  ),
                                  decoration: BoxDecoration(
                                    color: bgLight,
                                    borderRadius: BorderRadius.circular(6),
                                    border: Border.all(
                                      color: const Color(0xFFE2E8F0),
                                    ),
                                  ),
                                  child: Text(
                                    exemplar != null
                                        ? "Kode Eksemplar: ${exemplar['code']}"
                                        : "Eksemplar belum tersedia",
                                    style: TextStyle(
                                      color: exemplar != null
                                          ? accentBlue
                                          : Colors.grey.shade500,
                                      fontWeight: FontWeight.w600,
                                      fontSize: 11,
                                    ),
                                  ),
                                ),
                              ],
                            ),
                          ),
                        ],
                      ),
                    );
                  }),
                  const SizedBox(height: 20),
                ],
              ),
            );
          },
        );
      },
    );
  }

  Widget _bookCover(String? cover) {
    Widget fallback = Container(
      width: 55,
      height: 75,
      decoration: BoxDecoration(
        color: const Color(0xFFF1F5F9),
        borderRadius: BorderRadius.circular(8),
        border: Border.all(color: const Color(0xFFE2E8F0)),
      ),
      child: Icon(
        Icons.menu_book_rounded,
        size: 24,
        color: Colors.grey.shade400,
      ),
    );

    if (cover == null || cover.isEmpty) return fallback;

    return ClipRRect(
      borderRadius: BorderRadius.circular(8),
      child: Image.network(
        "http://sispussman3padang.my.id/storage/$cover",
        width: 55,
        height: 75,
        fit: BoxFit.cover,
        errorBuilder: (context, error, stackTrace) => fallback,
      ),
    );
  }

  Widget _buildPeminjaman() {
    final data = getPeminjaman();

    if (data.isEmpty) {
      return _buildEmptyState("Belum ada peminjaman aktif.");
    }

    return ListView.builder(
      padding: const EdgeInsets.all(16),
      itemCount: data.length,
      itemBuilder: (context, index) {
        final item = data[index];
        final bool isDipinjam = item['status'] == 'dipinjam';

        return Container(
          margin: const EdgeInsets.only(bottom: 16),
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.circular(20),
            boxShadow: [
              BoxShadow(
                color: Colors.black.withOpacity(0.03),
                blurRadius: 10,
                offset: const Offset(0, 4),
              ),
            ],
            border: Border.all(color: const Color(0xFFF1F5F9)),
          ),
          child: Padding(
            padding: const EdgeInsets.all(16),
            child: Column(
              children: [
                Row(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    SizedBox(
                      width: 85,
                      height: 80,
                      child: Stack(
                        children: [
                          for (
                            int i = 0;
                            i < item['details'].length && i < 3;
                            i++
                          )
                            Positioned(
                              left: i * 16,
                              top: i * 4,
                              child: Container(
                                decoration: BoxDecoration(
                                  boxShadow: [
                                    BoxShadow(
                                      color: Colors.black.withOpacity(0.08),
                                      blurRadius: 4,
                                      offset: const Offset(1, 1),
                                    ),
                                  ],
                                ),
                                child: _bookCover(
                                  item['details'][i]['book']['cover'],
                                ),
                              ),
                            ),
                          if (item['details'].length > 3)
                            Positioned(
                              right: 4,
                              bottom: 4,
                              child: Container(
                                padding: const EdgeInsets.all(6),
                                decoration: BoxDecoration(
                                  color: accentBlue,
                                  shape: BoxShape.circle,
                                ),
                                child: Text(
                                  "+${item['details'].length - 3}",
                                  style: const TextStyle(
                                    color: Colors.white,
                                    fontSize: 9,
                                    fontWeight: FontWeight.bold,
                                  ),
                                ),
                              ),
                            ),
                        ],
                      ),
                    ),
                    const SizedBox(width: 12),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            item['details'].length == 1
                                ? item['details'][0]['book']['title']
                                : "${item['details'].length} Buku Dipinjam",
                            style: TextStyle(
                              fontSize: 16,
                              fontWeight: FontWeight.bold,
                              color: primaryDark,
                            ),
                            maxLines: 1,
                            overflow: TextOverflow.ellipsis,
                          ),
                          const SizedBox(height: 4),
                          Text(
                            (item['details'] as List)
                                .map((e) => e['book']['title'].toString())
                                .join(', '),
                            maxLines: 2,
                            overflow: TextOverflow.ellipsis,
                            style: TextStyle(
                              color: Colors.grey.shade600,
                              fontSize: 12,
                              height: 1.3,
                            ),
                          ),
                          const SizedBox(height: 12),
                          SizedBox(
                            width: double.infinity,
                            child: Wrap(
                              alignment: WrapAlignment.spaceBetween,
                              crossAxisAlignment: WrapCrossAlignment.center,
                              spacing: 8,
                              runSpacing: 8,
                              children: [
                                Row(
                                  mainAxisSize: MainAxisSize.min,
                                  children: [
                                    Icon(
                                      Icons.calendar_today_rounded,
                                      size: 13,
                                      color: Colors.grey.shade500,
                                    ),
                                    const SizedBox(width: 4),
                                    Text(
                                      "Kembali: ${item['return_date']}",
                                      style: TextStyle(
                                        color: Colors.grey.shade600,
                                        fontSize: 12,
                                        fontWeight: FontWeight.w500,
                                      ),
                                    ),
                                  ],
                                ),
                                _statusBadge(item['status']),
                              ],
                            ),
                          ),
                        ],
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 16),
                Row(
                  children: [
                    Expanded(
                      child: OutlinedButton.icon(
                        icon: const Icon(Icons.visibility_outlined, size: 16),
                        label: const Text("Detail"),
                        style: OutlinedButton.styleFrom(
                          foregroundColor: primaryDark,
                          side: BorderSide(color: Colors.grey.shade300),
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(10),
                          ),
                          padding: const EdgeInsets.symmetric(vertical: 12),
                          textStyle: const TextStyle(
                            fontWeight: FontWeight.bold,
                            fontSize: 13,
                          ),
                        ),
                        onPressed: () => _showBorrowingDetail(item),
                      ),
                    ),
                    if (isDipinjam) ...[
                      const SizedBox(width: 10),
                      Expanded(
                        child: ElevatedButton.icon(
                          icon: const Icon(
                            Icons.qr_code_scanner_rounded,
                            size: 16,
                          ),
                          label: const Text("Kembalikan"),
                          style: ElevatedButton.styleFrom(
                            backgroundColor: primaryDark,
                            foregroundColor: Colors.white,
                            shape: RoundedRectangleBorder(
                              borderRadius: BorderRadius.circular(10),
                            ),
                            padding: const EdgeInsets.symmetric(vertical: 12),
                            elevation: 0,
                            textStyle: const TextStyle(
                              fontWeight: FontWeight.bold,
                              fontSize: 13,
                            ),
                          ),
                          onPressed: () async {
                            final result = await Navigator.push(
                              context,
                              MaterialPageRoute(
                                builder: (_) => ReturnQrPage(borrowing: item),
                              ),
                            );
                            if (result == true) loadBorrowings();
                          },
                        ),
                      ),
                    ],
                  ],
                ),
                if (isDipinjam) ...[
                  const SizedBox(height: 6),
                  TextButton.icon(
                    icon: const Icon(
                      Icons.warning_amber_rounded,
                      size: 15,
                      color: Colors.redAccent,
                    ),
                    label: const Text(
                      "Laporkan Buku Hilang",
                      style: TextStyle(
                        color: Colors.redAccent,
                        fontSize: 12,
                        fontWeight: FontWeight.w600,
                      ),
                    ),
                    style: TextButton.styleFrom(
                      minimumSize: const Size(double.infinity, 32),
                    ),
                    onPressed: () async {
                      final confirm = await showDialog<bool>(
                        context: context,
                        builder: (_) => AlertDialog(
                          title: const Text("Konfirmasi"),
                          content: const Text(
                            "Apakah Anda yakin ingin melaporkan eksemplar buku ini hilang?",
                          ),
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(16),
                          ),
                          actions: [
                            TextButton(
                              onPressed: () => Navigator.pop(context, false),
                              child: const Text("Batal"),
                            ),
                            ElevatedButton(
                              onPressed: () => Navigator.pop(context, true),
                              style: ElevatedButton.styleFrom(
                                backgroundColor: Colors.red,
                                foregroundColor: Colors.white,
                              ),
                              child: const Text("Ya, Laporkan"),
                            ),
                          ],
                        ),
                      );

                      if (confirm != true) return;

                      final result = await ApiService.reportLostBook(
                        item['id'],
                      );
                      if (!mounted) return;

                      ScaffoldMessenger.of(context).showSnackBar(
                        SnackBar(
                          content: Text(result['message']),
                          backgroundColor: primaryDark,
                        ),
                      );
                      loadBorrowings();
                    },
                  ),
                ],
              ],
            ),
          ),
        );
      },
    );
  }

  Widget _buildPengembalian() {
    final data = getPengembalian();

    if (data.isEmpty) {
      return _buildEmptyState("Belum ada pengembalian aktif.");
    }

    return ListView.builder(
      padding: const EdgeInsets.all(16),
      itemCount: data.length,
      itemBuilder: (context, index) {
        final item = data[index];

        return Container(
          margin: const EdgeInsets.only(bottom: 16),
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.circular(20),
            boxShadow: [
              BoxShadow(
                color: Colors.black.withOpacity(0.03),
                blurRadius: 10,
                offset: const Offset(0, 4),
              ),
            ],
            border: Border.all(color: const Color(0xFFF1F5F9)),
          ),
          child: Padding(
            padding: const EdgeInsets.all(16),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  children: [
                    Container(
                      width: 46,
                      height: 46,
                      decoration: BoxDecoration(
                        color: const Color(0xFFEFF6FF),
                        borderRadius: BorderRadius.circular(10),
                      ),
                      child: Icon(
                        Icons.hourglass_top_rounded,
                        color: accentBlue,
                        size: 20,
                      ),
                    ),
                    const SizedBox(width: 14),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            "Menunggu Verifikasi",
                            style: TextStyle(
                              fontSize: 16,
                              fontWeight: FontWeight.bold,
                              color: primaryDark,
                            ),
                          ),
                          const SizedBox(height: 4),
                          Row(
                            children: [
                              _statusBadge(item['status']),
                              const SizedBox(width: 8),
                              Text(
                                "•  ${item['details'].length} Buku",
                                style: TextStyle(
                                  color: Colors.grey.shade600,
                                  fontSize: 13,
                                  fontWeight: FontWeight.w600,
                                ),
                              ),
                            ],
                          ),
                        ],
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 14),
                Container(
                  width: double.infinity,
                  padding: const EdgeInsets.all(12),
                  decoration: BoxDecoration(
                    color: bgLight,
                    borderRadius: BorderRadius.circular(10),
                    border: Border.all(color: const Color(0xFFE2E8F0)),
                  ),
                  child: Text(
                    "Buku telah diserahkan. Silakan tunggu pustakawan memverifikasi barcode/QR pengembalian Anda.",
                    style: TextStyle(
                      color: Colors.grey.shade700,
                      fontSize: 12,
                      height: 1.4,
                    ),
                  ),
                ),
                const SizedBox(height: 14),
                OutlinedButton.icon(
                  icon: const Icon(Icons.visibility_outlined, size: 16),
                  label: const Text("Lihat Detail Buku"),
                  style: OutlinedButton.styleFrom(
                    foregroundColor: primaryDark,
                    side: BorderSide(color: Colors.grey.shade200),
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(10),
                    ),
                    minimumSize: const Size(double.infinity, 42),
                    textStyle: const TextStyle(
                      fontWeight: FontWeight.bold,
                      fontSize: 13,
                    ),
                  ),
                  onPressed: () => _showBorrowingDetail(item),
                ),
              ],
            ),
          ),
        );
      },
    );
  }

  void _showHistoryDetail(List histories) {
    if (histories.isEmpty) return;
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.white,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
      ),
      builder: (_) {
        return DraggableScrollableSheet(
          initialChildSize: 0.5,
          maxChildSize: 0.8,
          minChildSize: 0.4,
          expand: false,
          builder: (context, scrollController) {
            return Padding(
              padding: const EdgeInsets.symmetric(horizontal: 20),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const SizedBox(height: 12),
                  Center(
                    child: Container(
                      width: 40,
                      height: 5,
                      decoration: BoxDecoration(
                        color: Colors.grey.shade300,
                        borderRadius: BorderRadius.circular(10),
                      ),
                    ),
                  ),
                  const SizedBox(height: 20),
                  Text(
                    "Riwayat Transaksi",
                    style: TextStyle(
                      fontSize: 18,
                      fontWeight: FontWeight.w800,
                      color: primaryDark,
                    ),
                  ),
                  const SizedBox(height: 14),
                  Expanded(
                    child: ListView.builder(
                      controller: scrollController,
                      itemCount: histories.length,
                      itemBuilder: (context, index) {
                        final item = histories[index];
                        final isReturn = item['status'] == 'dikembalikan';

                        return Container(
                          decoration: BoxDecoration(
                            color: Colors.white,
                            borderRadius: BorderRadius.circular(16),
                            border: Border.all(color: const Color(0xFFE2E8F0)),
                          ),
                          padding: const EdgeInsets.all(14),
                          margin: const EdgeInsets.only(bottom: 12),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Row(
                                mainAxisAlignment:
                                    MainAxisAlignment.spaceBetween,
                                children: [
                                  _statusBadge(item['status']),
                                  Row(
                                    children: [
                                      _lateStatusBadge(item),
                                      const SizedBox(width: 8),
                                      Text(
                                        isReturn ? "Selesai" : "Ditolak",
                                        style: TextStyle(
                                          color: isReturn
                                              ? Colors.teal
                                              : Colors.red,
                                          fontWeight: FontWeight.bold,
                                          fontSize: 12,
                                        ),
                                      ),
                                    ],
                                  ),
                                ],
                              ),
                              const Divider(
                                height: 20,
                                color: Color(0xFFF1F5F9),
                              ),
                              ...List.generate(item['details'].length, (i) {
                                final book = item['details'][i]['book'];
                                // AMBIL DATA EKSEMPLAR DI SINI
                                final exemplar = item['details'][i]['exemplar'];

                                return Padding(
                                  padding: const EdgeInsets.symmetric(
                                    vertical: 6,
                                  ),
                                  child: Column(
                                    crossAxisAlignment:
                                        CrossAxisAlignment.start,
                                    children: [
                                      Row(
                                        children: [
                                          Icon(
                                            Icons.bookmark_added_rounded,
                                            size: 16,
                                            color: accentBlue,
                                          ),
                                          const SizedBox(width: 8),
                                          Expanded(
                                            child: Text(
                                              book['title'],
                                              style: TextStyle(
                                                fontWeight: FontWeight.w600,
                                                fontSize: 13,
                                                color: primaryDark,
                                              ),
                                              maxLines: 1,
                                              overflow: TextOverflow.ellipsis,
                                            ),
                                          ),
                                        ],
                                      ),
                                      const SizedBox(height: 4),
                                      // CETAK BADGE KODE EKSEMPLAR
                                      Padding(
                                        padding: const EdgeInsets.only(
                                          left: 24,
                                        ),
                                        child: Container(
                                          padding: const EdgeInsets.symmetric(
                                            horizontal: 8,
                                            vertical: 2,
                                          ),
                                          decoration: BoxDecoration(
                                            color: bgLight,
                                            borderRadius: BorderRadius.circular(
                                              4,
                                            ),
                                            border: Border.all(
                                              color: const Color(0xFFE2E8F0),
                                            ),
                                          ),
                                          child: Text(
                                            exemplar != null
                                                ? "Kode Eksemplar: ${exemplar['code']}"
                                                : "Eksemplar tidak tersedia",
                                            style: TextStyle(
                                              color: exemplar != null
                                                  ? accentBlue
                                                  : Colors.grey.shade500,
                                              fontWeight: FontWeight.w600,
                                              fontSize: 10,
                                            ),
                                          ),
                                        ),
                                      ),
                                    ],
                                  ),
                                );
                              }),
                              const SizedBox(height: 8),
                              Text(
                                "Pinjam: ${item['borrow_date']}  |  Kembali: ${item['return_date']}",
                                style: TextStyle(
                                  color: Colors.grey.shade500,
                                  fontSize: 11,
                                  fontWeight: FontWeight.w500,
                                ),
                              ),
                            ],
                          ),
                        );
                      },
                    ),
                  ),
                ],
              ),
            );
          },
        );
      },
    );
  }

  Widget _buildRiwayat() {
    final data = getRiwayat();

    return SingleChildScrollView(
      child: Column(
        children: [
          Container(
            margin: const EdgeInsets.all(16),
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(20),
              border: Border.all(color: const Color(0xFFF1F5F9)),
              boxShadow: [
                BoxShadow(
                  color: Colors.black.withOpacity(0.02),
                  blurRadius: 10,
                  offset: const Offset(0, 4),
                ),
              ],
            ),
            child: TableCalendar(
              firstDay: DateTime.utc(2024, 1, 1),
              lastDay: DateTime.utc(2035, 12, 31),
              focusedDay: _focusedDay,
              calendarFormat: CalendarFormat.month,
              availableCalendarFormats: const {CalendarFormat.month: 'Month'},
              selectedDayPredicate: (day) => isSameDay(_selectedDay, day),
              calendarStyle: CalendarStyle(
                todayDecoration: BoxDecoration(
                  color: accentBlue.withOpacity(0.12),
                  shape: BoxShape.circle,
                ),
                todayTextStyle: TextStyle(
                  color: accentBlue,
                  fontWeight: FontWeight.bold,
                ),
                selectedDecoration: BoxDecoration(
                  color: accentBlue,
                  shape: BoxShape.circle,
                ),
                markerDecoration: BoxDecoration(
                  color: primaryDark,
                  shape: BoxShape.circle,
                ),
                markersMaxCount: 1,
                outsideDaysVisible: false,
              ),
              headerStyle: HeaderStyle(
                titleCentered: true,
                titleTextStyle: TextStyle(
                  fontWeight: FontWeight.bold,
                  color: primaryDark,
                  fontSize: 16,
                ),
                formatButtonVisible: false,
                leftChevronIcon: Icon(
                  Icons.chevron_left_rounded,
                  color: primaryDark,
                ),
                rightChevronIcon: Icon(
                  Icons.chevron_right_rounded,
                  color: primaryDark,
                ),
              ),
              onDaySelected: (selectedDay, focusedDay) {
                setState(() {
                  _selectedDay = selectedDay;
                  _focusedDay = focusedDay;
                });

                final histories = data.where((item) {
                  final date = DateTime.parse(item['return_date']);
                  return isSameDay(date, selectedDay);
                }).toList();

                if (histories.isNotEmpty) {
                  _showHistoryDetail(histories);
                } else {
                  ScaffoldMessenger.of(context).showSnackBar(
                    const SnackBar(
                      content: Text(
                        "Tidak ada riwayat pengembalian di tanggal ini.",
                      ),
                      duration: Duration(seconds: 1),
                    ),
                  );
                }
              },
              eventLoader: (day) {
                return data.where((item) {
                  final date = DateTime.parse(item['return_date']);
                  return isSameDay(date, day);
                }).toList();
              },
            ),
          ),
          const SizedBox(height: 10),
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 32),
            child: Column(
              children: [
                Icon(
                  Icons.calendar_month_rounded,
                  size: 36,
                  color: Colors.grey.shade300,
                ),
                const SizedBox(height: 8),
                Text(
                  _selectedDay == null
                      ? "Pilih tanggal bertanda lingkaran pada kalender"
                      : "Ketuk tanggal bertanda lain untuk melihat transaksi",
                  textAlign: TextAlign.center,
                  style: TextStyle(
                    color: Colors.grey.shade500,
                    fontSize: 12,
                    height: 1.4,
                    fontWeight: FontWeight.w500,
                  ),
                ),
              ],
            ),
          ),
          const SizedBox(height: 30),
        ],
      ),
    );
  }

  Widget _buildEmptyState(String message) {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Icon(Icons.menu_book_rounded, size: 60, color: Colors.grey.shade300),
          const SizedBox(height: 12),
          Text(
            message,
            style: TextStyle(
              color: Colors.grey.shade500,
              fontSize: 13,
              fontWeight: FontWeight.w600,
            ),
          ),
        ],
      ),
    );
  }
}
