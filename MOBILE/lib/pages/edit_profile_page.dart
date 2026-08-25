import 'package:flutter/material.dart';
import '../services/api_service.dart';

class EditProfilePage extends StatefulWidget {
  final Map<String, dynamic> user;
  final Map<String, dynamic> profile;

  const EditProfilePage({
    super.key,
    required this.user,
    required this.profile,
  });

  @override
  State<EditProfilePage> createState() => _EditProfilePageState();
}

class _EditProfilePageState extends State<EditProfilePage> {

  final nameController = TextEditingController();
  final emailController = TextEditingController();
  final phoneController = TextEditingController();
  final addressController = TextEditingController();

  bool isLoading = false;

  final Color primaryColor = const Color(0xFF2563EB);
  final Color darkBlue = const Color(0xFF0F172A);
  final Color secondaryText = const Color(0xFF64748B);
  final Color bgGray = const Color(0xFFF8FAFC);

  @override
  void initState() {
    super.initState();

    nameController.text = widget.profile['name'] ?? '';
    emailController.text = widget.profile['email'] ?? '';
    phoneController.text = widget.profile['phone'] ?? '';
    addressController.text = widget.profile['address'] ?? '';
  }

  @override
  void dispose() {
    nameController.dispose();
    emailController.dispose();
    phoneController.dispose();
    addressController.dispose();
    super.dispose();
  }

  Future<void> saveProfile() async {

    setState(() {
      isLoading = true;
    });

    try {

      final result = await ApiService.updateProfile(

        userId: widget.user['id'],

        name: nameController.text,

        email: emailController.text,

        phone: phoneController.text,

        address: addressController.text,

      );

      if(!mounted) return;

      ScaffoldMessenger.of(context).showSnackBar(

        SnackBar(
          content: Text(result['message']),
        ),

      );

    if (result['success'] == true) {

  Navigator.pop(
    context,
    result['data'],
  );

}
    } catch(e){

      ScaffoldMessenger.of(context).showSnackBar(

        SnackBar(
          content: Text(e.toString()),
        ),

      );

    }

    if(mounted){

      setState(() {

        isLoading=false;

      });

    }

  }

  Widget buildField(

      String label,

      TextEditingController controller,

      IconData icon,

      {int maxLines=1}

      ){

    return Column(

      crossAxisAlignment: CrossAxisAlignment.start,

      children: [

        Text(

          label,

          style: TextStyle(

            fontWeight: FontWeight.w600,

            color: darkBlue,

          ),

        ),

        const SizedBox(height:8),

        TextField(

          controller: controller,

          maxLines: maxLines,

          decoration: InputDecoration(

            filled:true,

            fillColor:bgGray,

            prefixIcon: Icon(icon),

            border: OutlineInputBorder(

              borderRadius: BorderRadius.circular(14),

            ),

          ),

        ),

      ],

    );

  }

  @override
  Widget build(BuildContext context){

    return Scaffold(

      backgroundColor:bgGray,

      appBar: AppBar(

        title: Text(

          "Edit Profil",

          style: TextStyle(

            color:darkBlue,

            fontWeight: FontWeight.bold,

          ),

        ),

        backgroundColor: Colors.white,

      ),

      body: SingleChildScrollView(

        padding: const EdgeInsets.all(24),

        child: Column(

          children: [

            buildField(

              "Nama Lengkap",

              nameController,

              Icons.person,

            ),

            const SizedBox(height:20),

            buildField(

              "Email",

              emailController,

              Icons.email,

            ),

            const SizedBox(height:20),

            buildField(

              "Nomor HP",

              phoneController,

              Icons.phone,

            ),

            const SizedBox(height:20),

            buildField(

              "Alamat",

              addressController,

              Icons.location_on,

              maxLines:3,

            ),

            const SizedBox(height:32),

            SizedBox(

              width:double.infinity,

              height:52,

              child: ElevatedButton(

                style: ElevatedButton.styleFrom(

                  backgroundColor:primaryColor,

                ),

                onPressed:isLoading?null:saveProfile,

                child:isLoading

                    ?const CircularProgressIndicator(

                        color: Colors.white,

                      )

                    :const Text(

                        "Simpan Perubahan",

                        style: TextStyle(

                          color:Colors.white,

                          fontWeight: FontWeight.bold,

                        ),

                      ),

              ),

            ),

          ],

        ),

      ),

    );

  }

}