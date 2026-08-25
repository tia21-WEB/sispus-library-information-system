import 'package:flutter/material.dart';
import 'package:qr_flutter/qr_flutter.dart';

import '../services/api_service.dart';

class ReturnQrPage extends StatefulWidget {

  final Map borrowing;

  const ReturnQrPage({
    super.key,
    required this.borrowing,
  });

  @override
  State<ReturnQrPage> createState() =>
      _ReturnQrPageState();
}

class _ReturnQrPageState
    extends State<ReturnQrPage> {

  bool isLoading = false;

  Future<void> submitReturn() async {

    setState(() {
      isLoading = true;
    });

    try {

      final result =
          await ApiService.requestReturn(
        widget.borrowing['id'],
      );

      if (!mounted) return;

      ScaffoldMessenger.of(context)
          .showSnackBar(

        SnackBar(
          content: Text(
            result['message'] ??
                'Berhasil',
          ),
        ),
      );

      Navigator.pop(
        context,
        true,
      );

    } catch (e) {

      if (!mounted) return;

      ScaffoldMessenger.of(context)
          .showSnackBar(

        SnackBar(
          content: Text(
            e.toString(),
          ),
        ),
      );
    }

    if (!mounted) return;

    setState(() {
      isLoading = false;
    });
  }

  @override
  Widget build(BuildContext context) {

    return Scaffold(

      backgroundColor:
          const Color(0xFFF5F7FA),

      appBar: AppBar(

        elevation: 0,

        backgroundColor:
            Colors.white,

        title: const Text(

          "QR Pengembalian",

          style: TextStyle(
            color:
                Color(0xFF0C2340),
          ),
        ),

        iconTheme:
            const IconThemeData(
          color:
              Color(0xFF0C2340),
        ),
      ),

      body: SingleChildScrollView(

        child: Padding(

          padding:
              const EdgeInsets.all(
            24,
          ),

          child: Column(

            children: [

              const SizedBox(
                height: 20,
              ),

              Container(

                padding:
                    const EdgeInsets.all(
                  20,
                ),

                decoration:
                    BoxDecoration(

                  color:
                      Colors.white,

                  borderRadius:
                      BorderRadius.circular(
                    20,
                  ),

                  boxShadow: const [

                    BoxShadow(

                      color:
                          Colors.black12,

                      blurRadius:
                          8,

                      offset:
                          Offset(
                        0,
                        3,
                      ),
                    )
                  ],
                ),

                child: Column(

                  children: [

                    const Icon(

                      Icons.qr_code,

                      size: 60,

                      color:
                          Color(
                        0xFF2563EB,
                      ),
                    ),

                    const SizedBox(
                      height: 10,
                    ),

                    const Text(

                      "QR Pengembalian Buku",

                      style:
                          TextStyle(

                        fontSize:
                            20,

                        fontWeight:
                            FontWeight.bold,
                      ),
                    ),

                    const SizedBox(
                      height: 10,
                    ),

                    Text(

                      "Peminjaman #${widget.borrowing['id']}",

                      style:
                          const TextStyle(

                        color:
                            Colors.grey,
                      ),
                    ),

                    const SizedBox(
                      height: 20,
                    ),

                    QrImageView(

                      data:
                          widget.borrowing['id']
                              .toString(),

                      size: 220,

                      backgroundColor:
                          Colors.white,
                    ),

                    const SizedBox(
                      height: 20,
                    ),

                    const Text(

                      "Silakan scan QR ini untuk mengajukan pengembalian buku.",

                      textAlign:
                          TextAlign.center,

                      style: TextStyle(

                        fontSize: 14,

                        color:
                            Colors.grey,
                      ),
                    ),
                  ],
                ),
              ),

              const SizedBox(
                height: 30,
              ),

              Container(

                width:
                    double.infinity,

                padding:
                    const EdgeInsets.all(
                  18,
                ),

                decoration:
                    BoxDecoration(

                  color:
                      Colors.amber
                          .shade50,

                  borderRadius:
                      BorderRadius.circular(
                    16,
                  ),
                ),

                child: const Column(

                  children: [

                    Icon(
                      Icons.info_outline,
                      color:
                          Colors.orange,
                    ),

                    SizedBox(
                      height: 8,
                    ),

                    Text(

                      "Setelah scan QR, status peminjaman akan berubah menjadi Menunggu Pengembalian.",

                      textAlign:
                          TextAlign.center,
                    ),
                  ],
                ),
              ),

              const SizedBox(
                height: 30,
              ),

              SizedBox(

                width:
                    double.infinity,

                height: 55,

                child:
                    ElevatedButton(

                  style:
                      ElevatedButton
                          .styleFrom(

                    backgroundColor:
                        const Color(
                      0xFF0C2340,
                    ),

                    shape:
                        RoundedRectangleBorder(

                      borderRadius:
                          BorderRadius.circular(
                        16,
                      ),
                    ),
                  ),

                  onPressed:
                      isLoading
                          ? null
                          : submitReturn,

                  child:
                      isLoading

                          ? const CircularProgressIndicator(
                              color:
                                  Colors.white,
                            )

                          : const Text(

                              "Scan QR",

                              style:
                                  TextStyle(

                                fontSize:
                                    16,

                                color:
                                    Colors.white,

                                fontWeight:
                                    FontWeight.bold,
                              ),
                            ),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}