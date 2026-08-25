import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:smooth_page_indicator/smooth_page_indicator.dart';

import '../../screens/login_screen.dart';
import 'onboarding_model.dart';
import 'onboarding_page.dart';

class OnboardingScreen extends StatefulWidget {
  const OnboardingScreen({super.key});

  @override
  State<OnboardingScreen> createState() => _OnboardingScreenState();
}

class _OnboardingScreenState extends State<OnboardingScreen> {

  final PageController _controller = PageController();

  bool isLastPage = false;

  @override
  void dispose() {
    _controller.dispose();
    super.dispose();
  }

  void nextPage() {
    _controller.nextPage(
      duration: const Duration(milliseconds: 300),
      curve: Curves.easeInOut,
    );
  }

  Future<void> finishOnboarding() async {

  if (!mounted) return;

  Navigator.pushReplacement(
    context,
    MaterialPageRoute(
      builder: (_) => const LoginScreen(),
    ),
  );

}

  @override
  Widget build(BuildContext context) {
    return Scaffold(

      backgroundColor: Colors.white,

      body: SafeArea(

        child: Column(

          children: [

            /// SKIP
            Padding(
              padding: const EdgeInsets.symmetric(
                horizontal: 20,
                vertical: 15,
              ),
              child: Align(
                alignment: Alignment.centerRight,
                child: TextButton(
                  onPressed: finishOnboarding,
                  child: Text(
                    "Lewati",
                    style: GoogleFonts.poppins(
                      fontSize: 15,
                      fontWeight: FontWeight.w600,
                    ),
                  ),
                ),
              ),
            ),

            /// PAGEVIEW
            Expanded(
              child: PageView.builder(
                controller: _controller,
                itemCount: onboardingData.length,
                onPageChanged: (index) {
                  setState(() {
                    isLastPage = index == onboardingData.length - 1;
                  });
                },
                itemBuilder: (context, index) {
                  return OnboardingPage(
                    data: onboardingData[index],
                  );
                },
              ),
            ),

            /// INDICATOR
            SmoothPageIndicator(
              controller: _controller,
              count: onboardingData.length,
              effect: ExpandingDotsEffect(
                activeDotColor: Color(0xff1565FC),
                dotColor: Colors.grey.shade300,
                dotHeight: 10,
                dotWidth: 10,
              ),
            ),

            const SizedBox(height: 40),

            /// BUTTON
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 25),

              child: SizedBox(
                width: double.infinity,
                height: 55,

                child: ElevatedButton(

                  style: ElevatedButton.styleFrom(

                    backgroundColor: const Color(0xff1565FC),

                    shape: RoundedRectangleBorder(

                      borderRadius: BorderRadius.circular(15),

                    ),

                  ),

                  onPressed: () {

                    if (isLastPage) {

                      finishOnboarding();

                    } else {

                      nextPage();

                    }

                  },

                  child: Text(

                    isLastPage
                        ? "Mulai Sekarang"
                        : "Selanjutnya",

                    style: GoogleFonts.poppins(

                      color: Colors.white,

                      fontSize: 16,

                      fontWeight: FontWeight.bold,

                    ),

                  ),

                ),

              ),

            ),

            const SizedBox(height: 30),

          ],

        ),

      ),

    );
  }
}