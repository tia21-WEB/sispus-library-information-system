import 'package:flutter/material.dart';
import '../services/api_service.dart';

class ChangePasswordPage extends StatefulWidget {
  final Map<String, dynamic> user;

  const ChangePasswordPage({super.key, required this.user});

  @override
  State<ChangePasswordPage> createState() => _ChangePasswordPageState();
}

class _ChangePasswordPageState extends State<ChangePasswordPage> {
  final oldPasswordController = TextEditingController();
  final newPasswordController = TextEditingController();
  final confirmPasswordController = TextEditingController();

  bool isLoading = false;
  
  // State untuk melihat/sembunyikan password
  bool obscureOld = true;
  bool obscureNew = true;
  bool obscureConfirm = true;

  // Design Tokens / Warna Konsisten
  final Color primaryColor = const Color(0xFF2563EB); // Royal Blue
  final Color darkBlue = const Color(0xFF0F172A); // Slate 900
  final Color secondaryText = const Color(0xFF64748B); // Slate 500
  final Color bgGray = const Color(0xFFF8FAFC); // Slate 50

  Future<void> changePassword() async {
    if (oldPasswordController.text.isEmpty || 
        newPasswordController.text.isEmpty || 
        confirmPasswordController.text.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text("Semua kolom harus diisi")),
      );
      return;
    }

    if (newPasswordController.text != confirmPasswordController.text) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text("Konfirmasi password tidak sama")),
      );
      return;
    }

    setState(() {
      isLoading = true;
    });

    try {
      final result = await ApiService.changePassword(
        userId: widget.user['id'],
        oldPassword: oldPasswordController.text,
        newPassword: newPasswordController.text,
      );

      if (!mounted) return;

      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text(result['message'] ?? 'Berhasil mengubah password')),
      );

      if (result['success'] == true) {
        Navigator.pop(context);
      }
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text(e.toString())),
      );
    }

    if (mounted) {
      setState(() {
        isLoading = false;
      });
    }
  }

  @override
  void dispose() {
    oldPasswordController.dispose();
    newPasswordController.dispose();
    confirmPasswordController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: bgGray,
      appBar: AppBar(
        title: Text(
          "Ubah Password", 
          style: TextStyle(color: darkBlue, fontSize: 18, fontWeight: FontWeight.bold),
        ),
        leading: IconButton(
          icon: Icon(Icons.arrow_back_ios_new_rounded, color: darkBlue, size: 20),
          onPressed: () => Navigator.pop(context),
        ),
        backgroundColor: Colors.white,
        elevation: 0,
        bottom: PreferredSize(
          preferredSize: const Size.fromHeight(1),
          child: Container(color: const Color(0xFFE2E8F0), height: 1),
        ),
      ),
      body: SingleChildScrollView(
        physics: const BouncingScrollPhysics(),
        padding: const EdgeInsets.all(24),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Sub-header petunjuk teks
            Text(
              "Amankan Akun Anda",
              style: TextStyle(fontSize: 16, fontWeight: FontWeight.w800, color: darkBlue),
            ),
            const SizedBox(height: 4),
            Text(
              "Pastikan password baru Anda sulit ditebak dan tidak digunakan untuk akun lain.",
              style: TextStyle(fontSize: 13, color: secondaryText, height: 1.4),
            ),
            const SizedBox(height: 24),

            // --- CONTAINER UTAMA FORM ---
            Container(
              padding: const EdgeInsets.all(20),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(24),
                border: Border.all(color: const Color(0xFFE2E8F0)),
              ),
              child: Column(
                children: [
                  // Field Password Lama
                  _buildPasswordField(
                    controller: oldPasswordController,
                    label: "Password Lama",
                    obscureText: obscureOld,
                    icon: Icons.lock_outline_rounded,
                    onToggle: () => setState(() => obscureOld = !obscureOld),
                  ),
                  const SizedBox(height: 20),

                  // Field Password Baru
                  _buildPasswordField(
                    controller: newPasswordController,
                    label: "Password Baru",
                    obscureText: obscureNew,
                    icon: Icons.lock_open_rounded,
                    onToggle: () => setState(() => obscureNew = !obscureNew),
                  ),
                  const SizedBox(height: 20),

                  // Field Konfirmasi Password
                  _buildPasswordField(
                    controller: confirmPasswordController,
                    label: "Konfirmasi Password Baru",
                    obscureText: obscureConfirm,
                    icon: Icons.lock_reset_rounded,
                    onToggle: () => setState(() => obscureConfirm = !obscureConfirm),
                  ),
                  const SizedBox(height: 30),

                  // --- TOMBOL SIMPAN ---
                  SizedBox(
                    width: double.infinity,
                    height: 52,
                    child: ElevatedButton(
                      style: ElevatedButton.styleFrom(
                        backgroundColor: primaryColor,
                        foregroundColor: Colors.white,
                        elevation: 0,
                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(14)),
                        disabledBackgroundColor: primaryColor.withOpacity(0.6),
                      ),
                      onPressed: isLoading ? null : changePassword,
                      child: isLoading
                          ? const SizedBox(
                              height: 24,
                              width: 24,
                              child: CircularProgressIndicator(
                                color: Colors.white,
                                strokeWidth: 2.5,
                              ),
                            )
                          : const Text(
                              "Simpan Perubahan",
                              style: TextStyle(fontSize: 15, fontWeight: FontWeight.bold),
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

  // --- REUSABLE MODERN PASSWORD FIELD WIDGET ---
  Widget _buildPasswordField({
    required TextEditingController controller,
    required String label,
    required bool obscureText,
    required IconData icon,
    required VoidCallback onToggle,
  }) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          label,
          style: TextStyle(fontSize: 13, fontWeight: FontWeight.w600, color: darkBlue),
        ),
        const SizedBox(height: 8),
        TextField(
          controller: controller,
          obscureText: obscureText,
          style: TextStyle(color: darkBlue, fontSize: 15),
          decoration: InputDecoration(
            filled: true,
            fillColor: bgGray,
            prefixIcon: Icon(icon, color: secondaryText, size: 20),
            suffixIcon: IconButton(
              icon: Icon(
                obscureText ? Icons.visibility_off_outlined : Icons.visibility_outlined,
                color: secondaryText,
                size: 20,
              ),
              onPressed: onToggle,
            ),
            contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 16),
            enabledBorder: OutlineInputBorder(
              borderRadius: BorderRadius.circular(14),
              borderSide: const BorderSide(color: Color(0xFFE2E8F0)),
            ),
            focusedBorder: OutlineInputBorder(
              borderRadius: BorderRadius.circular(14),
              borderSide: BorderSide(color: primaryColor, width: 1.5),
            ),
          ),
        ),
      ],
    );
  }
}