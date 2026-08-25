import 'package:flutter/material.dart';
import '../services/api_service.dart';

class LeaderboardPage extends StatefulWidget {
  const LeaderboardPage({super.key});

  @override
  State<LeaderboardPage> createState() => _LeaderboardPageState();
}

class _LeaderboardPageState extends State<LeaderboardPage>
    with SingleTickerProviderStateMixin {
  late TabController tabController;
  List siswa = [];
  List guru = [];
  bool isLoading = true;

  // New Modern Blue & White Palette
  final Color primaryBlue = const Color(
    0xFF3B82F6,
  ); // Main Modern Blue (Blue 500)
  final Color deepBlue = const Color(
    0xFF1E3A8A,
  ); // Deep Blue for Text (Blue 900)
  final Color bgLight = const Color(
    0xFFF8FAFC,
  ); // Very light slate/grey background
  final Color lightBlueBg = const Color(
    0xFFE0F2FE,
  ); // Pale Blue background for elements
  final Color white = Colors.white;
  final Color accentBlue = const Color(0xFF60A5FA);

  @override
  void initState() {
    super.initState();
    tabController = TabController(length: 2, vsync: this);
    loadData();
  }

  Future<void> loadData() async {
    try {
      final siswaData = await ApiService.getLeaderboard("siswa");
      final guruData = await ApiService.getLeaderboard("guru");

      setState(() {
        siswa = siswaData;
        guru = guruData;
        isLoading = false;
      });
    } catch (e) {
      setState(() {
        isLoading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: bgLight,
      appBar: AppBar(
        title: Text(
          "Peringkat", // Updated title text for a more native feel
          style: TextStyle(
            color: deepBlue, // Title is now deep blue
            fontWeight: FontWeight.bold,
            fontSize: 22,
          ),
        ),
        centerTitle: true,
        backgroundColor: white,
        elevation: 0,
        scrolledUnderElevation: 0,
        bottom: PreferredSize(
          preferredSize: const Size.fromHeight(80),
          child: Container(
            margin: const EdgeInsets.fromLTRB(16, 0, 16, 16),
            padding: const EdgeInsets.all(4), // Added subtle padding
            decoration: BoxDecoration(
              color: lightBlueBg, // Pale blue for unselected background
              borderRadius: BorderRadius.circular(30),
            ),
            child: TabBar(
              controller: tabController,
              indicatorSize: TabBarIndicatorSize.tab,
              dividerColor: Colors.transparent,
              indicator: BoxDecoration(
                color: primaryBlue, // Selected tab is modern blue
                borderRadius: BorderRadius.circular(30),
                boxShadow: [
                  BoxShadow(
                    color: primaryBlue.withOpacity(0.3),
                    blurRadius: 10,
                    offset: const Offset(0, 5),
                  ),
                ],
              ),
              labelColor: white, // Text on selected tab is white
              unselectedLabelColor: deepBlue.withOpacity(
                0.7,
              ), // Text on unselected tab is deep blue
              labelStyle: const TextStyle(
                fontWeight: FontWeight.bold,
                fontSize: 15,
              ),
              tabs: const [
                Tab(text: "Siswa"),
                Tab(text: "Guru"),
              ],
            ),
          ),
        ),
      ),
      body: isLoading
          ? Center(
              child: CircularProgressIndicator(
                color: primaryBlue, // Loading spinner is modern blue
                strokeWidth: 3,
              ),
            )
          : TabBarView(
              controller: tabController,
              physics: const BouncingScrollPhysics(),
              children: [_buildLeaderboard(siswa), _buildLeaderboard(guru)],
            ),
    );
  }

  Widget _buildLeaderboard(List data) {
    if (data.isEmpty) {
      return Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(
              Icons.emoji_events_outlined,
              size: 64,
              color: accentBlue.withOpacity(0.5),
            ),
            const SizedBox(height: 12),
            Text(
              "Belum ada data peringkat",
              style: TextStyle(
                color: deepBlue.withOpacity(0.6),
                fontWeight: FontWeight.w600,
              ),
            ),
          ],
        ),
      );
    }

    return ListView.builder(
      physics: const BouncingScrollPhysics(),
      padding: const EdgeInsets.only(top: 16, left: 16, right: 16, bottom: 30),
      itemCount: data.length,
      itemBuilder: (context, index) {
        final user = data[index];
        final bool isRank1 = index == 0;
        final bool isRank2 = index == 1;
        final bool isRank3 = index == 2;
        final bool isTop3 = isRank1 || isRank2 || isRank3;

        // Custom styling for Rank Badges within the modern blue theme
        Color circleBg;
        Color circleBorder;
        String rankText;
        Color rankNumberColor = deepBlue.withOpacity(
          0.8,
        ); // Default number color

        if (isRank1) {
          circleBg = primaryBlue.withOpacity(0.15); // Lighter blue for 1st
          circleBorder = primaryBlue;
          rankText = "🥇"; // Keep emojis for top 3
        } else if (isRank2) {
          circleBg = primaryBlue.withOpacity(0.1); // Slightly lighter for 2nd
          circleBorder = primaryBlue.withOpacity(0.7);
          rankText = "🥈";
        } else if (isRank3) {
          circleBg = primaryBlue.withOpacity(0.08); // Lighter again for 3rd
          circleBorder = primaryBlue.withOpacity(0.5);
          rankText = "🥉";
        } else {
          circleBg = const Color(
            0xFFF1F5F9,
          ); // Light slate/grey for lower ranks
          circleBorder = Colors.transparent;
          rankText = "${index + 1}";
          rankNumberColor = deepBlue.withOpacity(0.6); // Slightly faded number
        }

        return Container(
          margin: const EdgeInsets.only(bottom: 12),
          padding: const EdgeInsets.all(16),
          decoration: BoxDecoration(
            color: white, // White card for content
            borderRadius: BorderRadius.circular(20),
            border: Border.all(
              color: isRank1
                  ? primaryBlue
                  : const Color(0xFFE2E8F0), // Stronger blue border for Rank 1
              width: isRank1 ? 2.0 : 1.0,
            ),
            boxShadow: [
              BoxShadow(
                color: isRank1
                    ? primaryBlue.withOpacity(
                        0.15,
                      ) // Stronger blue shadow for Rank 1
                    : Colors.black.withOpacity(0.02),
                blurRadius: 10,
                offset: const Offset(0, 4),
              ),
            ],
          ),
          child: Row(
            children: [
              // Modern Rank Badge
              Container(
                width: 48,
                height: 48,
                decoration: BoxDecoration(
                  color: circleBg,
                  shape: BoxShape.circle,
                  border: Border.all(color: circleBorder, width: 1.5),
                ),
                alignment: Alignment.center,
                child: Text(
                  rankText,
                  style: TextStyle(
                    fontSize: isTop3 ? 22 : 18,
                    fontWeight: FontWeight.bold,
                    color: rankNumberColor,
                  ),
                ),
              ),
              const SizedBox(width: 16),

              // User Details (Name & Badge)
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      user['name'] ?? '-',
                      style: TextStyle(
                        fontWeight: FontWeight.bold,
                        fontSize: 16,
                        color: deepBlue, // Name is deep blue
                      ),
                      maxLines: 1,
                      overflow: TextOverflow.ellipsis,
                    ),
                    const SizedBox(height: 4),
                    Text(
                      user['badge'] ?? '-',
                      style: TextStyle(
                        fontSize: 13,
                        color: deepBlue.withOpacity(
                          0.6,
                        ), // Badge detail is slightly lighter deep blue
                        fontWeight: FontWeight.w500,
                      ),
                    ),
                  ],
                ),
              ),

              // Points Container (Reworked to Blue and White)
              Container(
                padding: const EdgeInsets.symmetric(
                  horizontal: 14,
                  vertical: 8,
                ),
                decoration: BoxDecoration(
                  color: lightBlueBg, // Pale blue background
                  borderRadius: BorderRadius.circular(20),
                ),
                child: Row(
                  children: [
                    Icon(
                      Icons.star_rounded,
                      color: primaryBlue, // Vibrant blue star
                      size: 18,
                    ),
                    const SizedBox(width: 6),
                    Text(
                      "${user['points']}",
                      style: TextStyle(
                        fontWeight: FontWeight.bold,
                        color: deepBlue, // Deep blue text
                        fontSize: 14,
                      ),
                    ),
                  ],
                ),
              ),
            ],
          ),
        );
      },
    );
  }
}
