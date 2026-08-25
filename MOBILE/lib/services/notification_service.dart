import 'dart:math';

import 'package:firebase_messaging/firebase_messaging.dart';
import 'package:flutter_local_notifications/flutter_local_notifications.dart';

class NotificationService {
  static final FlutterLocalNotificationsPlugin notifications =
      FlutterLocalNotificationsPlugin();

  static Future<void> init() async {
    const AndroidInitializationSettings android =
        AndroidInitializationSettings('@mipmap/ic_launcher');

    const InitializationSettings settings =
        InitializationSettings(android: android);

    await notifications.initialize(settings);
  }

  static Future<void> show(RemoteMessage message) async {
    const AndroidNotificationDetails android =
        AndroidNotificationDetails(
      'sispus_channel',
      'SISPUS Notification',
      channelDescription: 'Notifikasi SISPUS',
      importance: Importance.max,
      priority: Priority.high,
    );

    const NotificationDetails details =
        NotificationDetails(android: android);

    await notifications.show(
      Random().nextInt(999999),
      message.notification?.title ?? '',
      message.notification?.body ?? '',
      details,
    );
  }
}