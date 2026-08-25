import 'package:flutter/material.dart';
import '../services/api_service.dart';
import '../screens/login_screen.dart';
import 'leaderboard_page.dart';
import 'change_password_page.dart';
import 'edit_profile_page.dart';

class ProfilePage extends StatefulWidget {
  final Map<String, dynamic> user;

  const ProfilePage({super.key, required this.user});
  @override
  State<ProfilePage> createState() => _ProfilePageState();
}

class _ProfilePageState extends State<ProfilePage> {
  Map<String, dynamic> ranking = {};
Map<String, dynamic> profile = {};
  
  // Design Tokens / Konstanta Warna Modern
  final Color primaryColor = const Color(0xFF2563EB); // Royal Blue
  final Color darkBlue = const Color(0xFF0F172A); // Slate 900
  final Color secondaryText = const Color(0xFF64748B); // Slate 500
  final Color bgGray = const Color(0xFFF8FAFC); // Slate 50

  @override
  void initState() {
    super.initState();
    loadProfile();
    loadRanking();
  }

  Future<void> loadProfile() async {
    try {
      final data = await ApiService.getProfile(widget.user['id']);
      setState(() {
        profile = data['data'];
      });
    } catch (e) {
      print(e);
    }
  }

  Future<void> loadRanking() async {
    try {
      final data = await ApiService.getRanking(widget.user['id']);
      setState(() {
        ranking = data;
      });
    } catch (e) {
      print(e);
    }
  }

  @override
  Widget build(BuildContext context) {
    final user = widget.user;
    
    return Scaffold(
      backgroundColor: bgGray,
      body: SingleChildScrollView(
        physics: const BouncingScrollPhysics(),
        child: Column(
          children: [
            // --- HEADER PROFILE GRADIENT ---
            Container(
              width: double.infinity,
              padding: const EdgeInsets.fromLTRB(24, 50, 24, 40),
              decoration: BoxDecoration(
                gradient: LinearGradient(
                  colors: [darkBlue, const Color(0xFF1E3A8A)],
                  begin: Alignment.topLeft,
                  end: Alignment.bottomRight,
                ),
                borderRadius: const BorderRadius.only(
                  bottomLeft: Radius.circular(32),
                  bottomRight: Radius.circular(32),
                ),
                boxShadow: [
                  BoxShadow(
                    color: darkBlue.withOpacity(0.15),
                    blurRadius: 20,
                    offset: const Offset(0, 10),
                  )
                ],
              ),
              child: Column(
                children: [
                  // Avatar Bulat dengan Border Transparan Halus
                  Container(
                    decoration: BoxDecoration(
                      shape: BoxShape.circle,
                      border: Border.all(color: Colors.white.withOpacity(0.2), width: 4),
                    ),
                    child: CircleAvatar(
                      radius: 46,
                      backgroundColor: Colors.white,
                      child: Text(
                        (profile.isEmpty
        ? user['name']
        : profile['name'])
    .toString()
    .substring(0,1).toUpperCase(),
                        style: TextStyle(
                          fontSize: 32,
                          fontWeight: FontWeight.w800,
                          color: darkBlue,
                        ),
                      ),
                    ),
                  ),
                  const SizedBox(height: 16),
                  
                  // Nama Pengguna
                  Text(
  profile.isEmpty
      ? user['name']
      : profile['name'],
                    textAlign: TextAlign.center,
                    style: const TextStyle(
                      color: Colors.white,
                      fontSize: 22,
                      fontWeight: FontWeight.w800,
                      letterSpacing: 0.5,
                    ),
                  ),
                  const SizedBox(height: 4),
                  
                  // Role Badge
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 4),
                    decoration: BoxDecoration(
                      color: Colors.white.withOpacity(0.12),
                      borderRadius: BorderRadius.circular(30),
                    ),
                    child: Text(
                      user['role'].toString().toUpperCase(),
                      style: const TextStyle(
                        color: Colors.white70,
                        fontSize: 12,
                        fontWeight: FontWeight.w600,
                        letterSpacing: 1,
                      ),
                    ),
                  ),
                  const SizedBox(height: 20),
                  
                  // Medali Peringkat / Badge Pencapaian
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 18, vertical: 8),
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(30),
                      boxShadow: [
                        BoxShadow(
                          color: Colors.black.withOpacity(0.08),
                          blurRadius: 10,
                          offset: const Offset(0, 4),
                        )
                      ],
                    ),
                    child: Row(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        const Icon(Icons.workspace_premium_rounded, color: Colors.orange, size: 20),
                        const SizedBox(width: 8),
                        Text(
                          profile.isEmpty ? "Bronze Badge" : "${profile['badge']} Badge",
                          style: TextStyle(
                            fontWeight: FontWeight.w800,
                            color: darkBlue,
                            fontSize: 13,
                          ),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            ),

            // --- AREA KONTEN DATA ---
            Padding(
              padding: const EdgeInsets.all(24),
              child: Column(
                children: [
                  // --- KARTU STATISTIK (POIN & RANKING) ---
                  Row(
                    children: [
                      Expanded(
                        child: _buildStatCard(
                          profile.isEmpty ? "0" : "${profile['points']}",
                          "Total Poin",
                          Icons.stars_rounded,
                          const Color(0xFFF59E0B),
                        ),
                      ),
                      const SizedBox(width: 16),
                      Expanded(
  child: _buildStatCard(
    ranking.isEmpty
        ? "..."
        : "#${ranking['rank']}",

    "dari ${ranking['total'] ?? 0} "
    "${widget.user['role'] == 'guru'
        ? 'Guru'
        : 'Siswa'}",

    Icons.emoji_events_rounded,

    const Color.fromARGB(255, 16, 120, 185),
  ),
),
                    ],
                  ),
                  const SizedBox(height: 24),

                  // --- BLOK INFORMASI AKUN ---
                  Container(
                    padding: const EdgeInsets.all(20),
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(24),
                      border: Border.all(color: const Color(0xFFE2E8F0)),
                    ),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          "Informasi Akun",
                          style: TextStyle(
                            fontSize: 16,
                            fontWeight: FontWeight.w800,
                            color: darkBlue,
                          ),
                        ),
                        const SizedBox(height: 12),
                        _buildInfoTile(Icons.person_outline_rounded, "Nama Lengkap", user['name'] ?? '-'),
                        _buildInfoTile(Icons.alternate_email_rounded, "Alamat Email", profile.isEmpty ? "-" : profile['email']),
                        _buildInfoTile(Icons.phone_android_rounded, "Nomor HP", profile.isEmpty ? "-" : profile['phone']),
                        _buildInfoTile(Icons.location_on_outlined, "Alamat Tinggal", profile.isEmpty ? "-" : profile['address']),
                      ],
                    ),
                  ),
                  const SizedBox(height: 24),

                  // --- BLOK MENU NAVIGASI ---
                  Container(
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(24),
                      border: Border.all(color: const Color(0xFFE2E8F0)),
                    ),
                    child: Column(
                      children: [
                        _buildMenuTile(
                          icon: Icons.leaderboard_rounded,
                          title: "Leaderboard Peringkat",
                          onTap: () {
                            Navigator.push(
                              context,
                              MaterialPageRoute(builder: (_) => const LeaderboardPage()),
                            );
                          },
                        ),
                        Padding(
                          padding: const EdgeInsets.symmetric(horizontal: 20),
                          child: Divider(color: const Color(0xFFF1F5F9), height: 1),
                        ),
                        _buildMenuTile(
  icon: Icons.edit_rounded,
  title: "Edit Profil",
  onTap: () async {

    final result = await Navigator.push(

      context,

      MaterialPageRoute(

        builder: (_) => EditProfilePage(

          user: widget.user,

          profile: profile,

        ),

      ),

    );

   if (result != null) {

  widget.user['name'] = result['name'];
  widget.user['email'] = result['email'];
  widget.user['phone'] = result['phone'];
  widget.user['address'] = result['address'];

  loadProfile();

  setState(() {});
}

  },
),

Padding(
  padding: const EdgeInsets.symmetric(horizontal: 20),
  child: Divider(
    color: const Color(0xFFF1F5F9),
    height: 1,
  ),
),
                        _buildMenuTile(
                          icon: Icons.lock_reset_rounded,
                          title: "Ubah Password Akun",
                          onTap: () {
                            Navigator.push(
                              context,
                              MaterialPageRoute(builder: (_) => ChangePasswordPage(user: widget.user)),
                            );
                          },
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(height: 32),

                  // --- TOMBOL LOGOUT UTAMA (FIXED) ---
                  SizedBox(
                    width: double.infinity,
                    height: 52,
                    child: ElevatedButton(
                      style: ElevatedButton.styleFrom(
                        backgroundColor: const Color(0xFFFEE2E2), // Soft Red Light
                        foregroundColor: const Color(0xFFEF4444), // Crimson Red
                        elevation: 0,
                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                      ),
                      onPressed: () async {
                        final logout = await showDialog<bool>(
                          context: context,
                          builder: (_) => AlertDialog(
                            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
                            title: const Text("Konfirmasi Logout", style: TextStyle(fontWeight: FontWeight.bold)),
                            content: const Text("Apakah Anda yakin ingin keluar dari sistem perpustakaan?"),
                            actions: [
                              TextButton(
                                onPressed: () => Navigator.pop(context, false),
                                child: Text("Batal", style: TextStyle(color: secondaryText)),
                              ),
                              ElevatedButton(
                                style: ElevatedButton.styleFrom(
                                  backgroundColor: const Color(0xFFEF4444),
                                  foregroundColor: Colors.white,
                                  elevation: 0,
                                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
                                ),
                                onPressed: () => Navigator.pop(context, true),
                                child: const Text("Keluar"),
                              ),
                            ],
                          ),
                        );

                        if (logout != true) return;
                        if (!context.mounted) return;

                        Navigator.pushAndRemoveUntil(
                          context,
                          MaterialPageRoute(builder: (_) => const LoginScreen()),
                          (route) => false,
                        );
                      }, // Penutup parameter onPressed yang benar
                      child: const Row(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          Icon(Icons.logout_rounded, size: 20),
                          SizedBox(width: 8),
                          Text(
                            "Keluar dari Aplikasi",
                            style: TextStyle(fontSize: 15, fontWeight: FontWeight.bold),
                          ),
                        ],
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

  // --- REUSABLE WIDGET: STAT CARD ---
  Widget _buildStatCard(String value, String title, IconData icon, Color accentColor) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(20),
        border: Border.all(color: const Color(0xFFE2E8F0)),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          CircleAvatar(
            radius: 18,
            backgroundColor: accentColor.withOpacity(0.12),
            child: Icon(icon, color: accentColor, size: 20),
          ),
          const SizedBox(height: 14),
          Text(
            value,
            style: TextStyle(
              color: darkBlue,
              fontSize: 22,
              fontWeight: FontWeight.w800,
            ),
          ),
          const SizedBox(height: 2),
          Text(
            title,
            style: TextStyle(color: secondaryText, fontSize: 12, fontWeight: FontWeight.w500),
          ),
        ],
      ),
    );
  }

  // --- REUSABLE WIDGET: INFO TILE ---
  Widget _buildInfoTile(IconData icon, String title, String value) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 10),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Icon(icon, color: primaryColor, size: 22),
          const SizedBox(width: 14),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  title,
                  style: TextStyle(fontSize: 11, color: secondaryText, fontWeight: FontWeight.w500),
                ),
                const SizedBox(height: 2),
                Text(
                  value,
                  style: TextStyle(fontSize: 14, color: darkBlue, fontWeight: FontWeight.w600),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  // --- REUSABLE WIDGET: MENU SELECTION TILE ---
  Widget _buildMenuTile({required IconData icon, required String title, required VoidCallback onTap}) {
    return ListTile(
      contentPadding: const EdgeInsets.symmetric(horizontal: 20, vertical: 4),
      leading: Container(
        padding: const EdgeInsets.all(8),
        decoration: BoxDecoration(
          color: const Color(0xFFF1F5F9),
          borderRadius: BorderRadius.circular(10),
        ),
        child: Icon(icon, color: darkBlue, size: 20),
      ),
      title: Text(
        title,
        style: TextStyle(color: darkBlue, fontSize: 14, fontWeight: FontWeight.w600),
      ),
      trailing: Icon(Icons.arrow_forward_ios_rounded, size: 14, color: secondaryText),
      onTap: onTap,
    );
  }
}