class OnboardingModel {
  final String image;
  final String title;
  final String description;

  OnboardingModel({
    required this.image,
    required this.title,
    required this.description,
  });
}

final List<OnboardingModel> onboardingData = [
  OnboardingModel(
    image: "assets/images/onboarding/page1.png",
    title: "Jelajahi Buku",
    description:
        "Temukan berbagai koleksi buku perpustakaan sekolah dengan cepat dan mudah.",
  ),
  OnboardingModel(
    image: "assets/images/onboarding/page2.png",
    title: "Pinjam Buku Online",
    description:
        "Ajukan peminjaman buku langsung dari aplikasi tanpa harus datang ke perpustakaan.",
  ),
  OnboardingModel(
    image: "assets/images/onboarding/page3.png",
    title: "Raih Badge & Poin",
    description:
        "Kumpulkan poin dan badge dari aktivitas membaca dan jadilah pembaca terbaik.",
  ),
];
