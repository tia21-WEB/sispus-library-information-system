class CartService {
  static Map<int, List<Map<String, dynamic>>> carts = {};

  static void addBook({
    required int userId,
    required Map<String, dynamic> book,
    required String loanType,
  }) {
    carts.putIfAbsent(userId, () => []);

    final books = carts[userId]!;

    bool exists = books.any(
      (b) => b['id'] == book['id'] && b['loan_type'] == loanType,
    );

    if (!exists) {
      books.add({
        'id': book['id'],
        'title': book['title'],
        'author': book['author'],
        'cover': book['cover'],
        'publisher': book['publisher'],
        'publication_year': book['publication_year'],
        'loan_type': loanType,
      });
    }
  }

  static void removeBook(int userId, int id, String loanType) {
    carts[userId]?.removeWhere(
      (b) => b['id'] == id && b['loan_type'] == loanType,
    );
  }

  static void clear(int userId) {
    carts[userId]?.clear();
  }

  static int total(int userId) {
    return carts[userId]?.length ?? 0;
  }
  static List<Map<String, dynamic>> getBooks(int userId) {
  carts.putIfAbsent(userId, () => []);
  return carts[userId]!;
}
}
