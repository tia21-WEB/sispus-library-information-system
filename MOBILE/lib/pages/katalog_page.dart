import 'package:flutter/material.dart';
import '../services/api_service.dart';
import 'book_detail_page.dart';
import 'cart_page.dart';
import '../services/cart_service.dart';

class KatalogPage extends StatefulWidget {
  final Map<String, dynamic> user;

  const KatalogPage({super.key, required this.user});

  @override
  State<KatalogPage> createState() => _KatalogPageState();
}

class _KatalogPageState extends State<KatalogPage> {
  List books = [];
  List filteredBooks = [];
  List<String> categories = ['Semua'];
  String selectedCategory = 'Semua';
  bool isLoading = true;
  TextEditingController searchController = TextEditingController();

  final Color primaryColor = const Color(0xFF2563EB); // Royal Blue
  final Color backgroundColor = const Color(0xFFF8FAFC); // Slate Light

  @override
  void initState() {
    super.initState();
    getBooks();
  }

  Future<void> getBooks() async {
    try {
      final data = await ApiService.getBooks();
      final categoryList = ['Semua'];

      for (var book in data) {
        final category = book['category']?['name'];
        if (category != null && !categoryList.contains(category)) {
          categoryList.add(category);
        }
      }

      setState(() {
        books = data;
        filteredBooks = data;
        categories = List<String>.from(categoryList);
        isLoading = false;
      });
    } catch (e) {
      debugPrint(e.toString());
      setState(() {
        isLoading = false;
      });
    }
  }

  void searchBooks(String keyword) {
    setState(() {
      filteredBooks = books.where((book) {
        final title = book['title'].toString().toLowerCase();
        final author = book['author'].toString().toLowerCase();
        final category =
            book['category']?['name']?.toString().toLowerCase() ?? '';

        final matchSearch =
            title.contains(keyword.toLowerCase()) ||
            author.contains(keyword.toLowerCase());
        final matchCategory = selectedCategory == 'Semua'
            ? true
            : category == selectedCategory.toLowerCase();

        return matchSearch && matchCategory;
      }).toList();
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: backgroundColor,
      body: SafeArea(
        child: Column(
          children: [
            // --- HEADER SECTION ---
            Padding(
              padding: const EdgeInsets.fromLTRB(20, 24, 20, 16),
              child: Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  const Text(
                    "Katalog Buku",
                    style: TextStyle(
                      fontSize: 26,
                      fontWeight: FontWeight.bold,
                      color: Color(0xFF1E293B),
                    ),
                  ),

                  Row(
                    children: [
                      IconButton(
                        icon: Stack(
                          children: [
                            const Icon(Icons.shopping_cart),

                            if (CartService.total(widget.user['id']) > 0)
                              Positioned(
                                right: 0,

                                child: Container(
                                  padding: const EdgeInsets.all(4),

                                  decoration: const BoxDecoration(
                                    color: Colors.red,

                                    shape: BoxShape.circle,
                                  ),

                                  child: Text(
                                    CartService.total.toString(),

                                    style: const TextStyle(
                                      color: Colors.white,

                                      fontSize: 10,
                                    ),
                                  ),
                                ),
                              ),
                          ],
                        ),

                        onPressed: () {
                          Navigator.push(
                            context,

                            MaterialPageRoute(
                              builder: (_) => CartPage(user: widget.user),
                            ),
                          );
                        },
                      ),

                      Icon(
                        Icons.library_books_rounded,
                        color: primaryColor,
                        size: 28,
                      ),
                    ],
                  ),
                ],
              ),
            ),

            // --- SEARCH BAR SECTION ---
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 20),
              child: Container(
                decoration: BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.circular(16),
                  boxShadow: [
                    BoxShadow(
                      color: Colors.black.withOpacity(0.04),
                      blurRadius: 16,
                      offset: const Offset(0, 4),
                    ),
                  ],
                ),
                child: TextField(
                  controller: searchController,
                  onChanged: searchBooks,
                  style: const TextStyle(fontSize: 15),
                  decoration: InputDecoration(
                    hintText: "Cari judul atau penulis...",
                    hintStyle: TextStyle(color: Colors.grey[400], fontSize: 15),
                    prefixIcon: Icon(
                      Icons.search_rounded,
                      color: primaryColor,
                      size: 24,
                    ),
                    contentPadding: const EdgeInsets.symmetric(vertical: 16),
                    border: InputBorder.none,
                  ),
                ),
              ),
            ),

            const SizedBox(height: 20),

            // --- CATEGORIES FILTER ---
            SizedBox(
              height: 44,
              child: ListView.separated(
                scrollDirection: Axis.horizontal,
                padding: const EdgeInsets.symmetric(horizontal: 20),
                itemCount: categories.length,
                separatorBuilder: (_, __) => const SizedBox(width: 8),
                itemBuilder: (context, index) {
                  final category = categories[index];
                  final isSelected = selectedCategory == category;

                  return FilterChip(
                    label: Text(category),
                    selected: isSelected,
                    onSelected: (_) {
                      setState(() {
                        selectedCategory = category;
                      });
                      searchBooks(searchController.text);
                    },
                    selectedColor: primaryColor,
                    checkmarkColor: Colors.white,
                    backgroundColor: Colors.white,
                    labelStyle: TextStyle(
                      color: isSelected
                          ? Colors.white
                          : const Color(0xFF475569),
                      fontWeight: isSelected
                          ? FontWeight.bold
                          : FontWeight.normal,
                      fontSize: 13,
                    ),
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(12),
                      side: BorderSide(
                        color: isSelected
                            ? Colors.transparent
                            : const Color(0xFFE2E8F0),
                      ),
                    ),
                    elevation: isSelected ? 2 : 0,
                    shadowColor: primaryColor.withOpacity(0.4),
                  );
                },
              ),
            ),

            const SizedBox(height: 16),

            // --- MAIN CONTENT (GRID BOOK) ---
            Expanded(
              child: isLoading
                  ? Center(
                      child: CircularProgressIndicator(color: primaryColor),
                    )
                  : filteredBooks.isEmpty
                  ? _buildEmptyState()
                  : GridView.builder(
                      padding: const EdgeInsets.fromLTRB(20, 0, 20, 20),
                      itemCount: filteredBooks.length,
                      gridDelegate:
                          const SliverGridDelegateWithFixedCrossAxisCount(
                            crossAxisCount: 2,
                            crossAxisSpacing: 16,
                            mainAxisSpacing: 16,
                            childAspectRatio:
                                0.55, // Proporsi pas, tidak terpotong
                          ),
                      itemBuilder: (context, index) {
                        final book = filteredBooks[index];
                        return _buildBookCard(book, index);
                      },
                    ),
            ),
          ],
        ),
      ),
    );
  }

  // Card Widget yang sudah disatukan ke dalam state class
  Widget _buildBookCard(Map<String, dynamic> book, int index) {
    return Material(
      color: Colors.white,
      borderRadius: BorderRadius.circular(16),
      shadowColor: Colors.black.withOpacity(0.2),
      elevation: 1.5,
      child: InkWell(
        borderRadius: BorderRadius.circular(16),
        onTap: () {
          Navigator.push(
            context,
            MaterialPageRoute(
              builder: (context) =>
                  BookDetailPage(book: book, user: widget.user),
            ),
          );
        },
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Cover Buku dengan loading builder yang mulus
            Expanded(
              flex: 6,
              child: Container(
                width: double.infinity,
                decoration: BoxDecoration(
                  color: Colors.grey[100],
                  borderRadius: const BorderRadius.vertical(
                    top: Radius.circular(16),
                  ),
                ),
                child: ClipRRect(
                  borderRadius: const BorderRadius.vertical(
                    top: Radius.circular(16),
                  ),
                  child: Image.network(
                    "http://sispussman3padang.my.id/storage/${book['cover']}",
                    fit: BoxFit.cover,
                    loadingBuilder: (context, child, progress) {
                      if (progress == null) return child;
                      return Center(
                        child: CircularProgressIndicator(
                          strokeWidth: 2,
                          color: primaryColor.withOpacity(0.5),
                        ),
                      );
                    },
                    errorBuilder: (context, error, stackTrace) {
                      return Center(
                        child: Icon(
                          Icons.book_rounded,
                          size: 40,
                          color: Colors.grey[400],
                        ),
                      );
                    },
                  ),
                ),
              ),
            ),
            // Detail Informasi Buku
            Expanded(
              flex: 5,
              child: Padding(
                padding: const EdgeInsets.all(12),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      book['category']?['name']?.toUpperCase() ?? 'UMUM',
                      style: TextStyle(
                        fontSize: 10,
                        fontWeight: FontWeight.bold,
                        color: primaryColor,
                        letterSpacing: 0.5,
                      ),
                    ),
                    const SizedBox(height: 4),
                    Text(
                      book['title'] ?? '-',
                      maxLines: 2,
                      overflow: TextOverflow.ellipsis,
                      style: const TextStyle(
                        fontSize: 14,
                        fontWeight: FontWeight.bold,
                        color: Color(0xFF1E293B),
                        height: 1.2,
                      ),
                    ),
                    const SizedBox(height: 2),
                    Text(
                      book['author'] ?? '-',
                      maxLines: 1,
                      overflow: TextOverflow.ellipsis,
                      style: TextStyle(color: Colors.grey[500], fontSize: 11),
                    ),
                    const Spacer(),
                    // Badge Stok Buku Modern
                    Container(
                      padding: const EdgeInsets.symmetric(
                        horizontal: 8,
                        vertical: 4,
                      ),
                      decoration: BoxDecoration(
                        color: const Color(0xFFF1F5F9),
                        borderRadius: BorderRadius.circular(8),
                      ),
                      child: Row(
                        mainAxisSize: MainAxisSize.min,
                        children: [
                          const Icon(
                            Icons.collections_bookmark_rounded,
                            size: 12,
                            color: Color(0xFF64748B),
                          ),
                          const SizedBox(width: 4),
                          Text(
                            "${book['stock']} Tersedia",
                            style: const TextStyle(
                              fontSize: 10,
                              fontWeight: FontWeight.w600,
                              color: Color(0xFF475569),
                            ),
                          ),
                        ],
                      ),
                    ),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildEmptyState() {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Icon(Icons.menu_book_rounded, size: 64, color: Colors.grey[300]),
          const SizedBox(height: 12),
          Text(
            "Buku tidak ditemukan",
            style: TextStyle(
              fontSize: 16,
              fontWeight: FontWeight.w600,
              color: Colors.grey[600],
            ),
          ),
          const SizedBox(height: 4),
          Text(
            "Coba cari kata kunci atau kategori lain.",
            style: TextStyle(fontSize: 13, color: Colors.grey[400]),
          ),
        ],
      ),
    );
  }
}
