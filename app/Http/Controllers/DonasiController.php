// lib/app/services/donation_service.dart

import 'dart:convert';
import 'package:get/get.dart';
import 'package:http/http.dart' as http;
import '../config/api_config.dart';
import '../controllers/auth_controller.dart';

class DonationService {
  final String baseUrl = ApiConfig.baseUrl;

  Future<Map<String, dynamic>> donate({
    required int streamerId,
    required int nominal,
    required String pesan,
    int adminFee = 1500,
    int fiturTotal = 0,
    int grandTotal = 0,
    String metode = 'wallet',
    String? guestName,
    String? guestPhone,
    List<String> fitur = const [],
  }) async {
    try {
      final authController = Get.find<AuthController>();
      final token = authController.token.value;

      // Hitung grand total jika belum diset
      if (grandTotal == 0) {
        grandTotal = nominal + fiturTotal + adminFee;
      }

      final response = await http.post(
        Uri.parse('$baseUrl/api/donasi/store'),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          if (token.isNotEmpty) 'Authorization': 'Bearer $token',
        },
        body: jsonEncode({
          'streamer_id': streamerId,
          'nominal': nominal,
          'pesan': pesan,
          'admin_fee': adminFee,
          'fitur_total': fiturTotal,
          'grand_total': grandTotal,
          'metode': metode,
          'guest_name': guestName ?? '',
          'guest_phone': guestPhone ?? '',
          'fitur': fitur,
        }),
      );

      print('=== DONASI RESPONSE ===');
      print('Status: ${response.statusCode}');
      print('Body: ${response.body}');

      if (response.statusCode == 200 || response.statusCode == 201) {
        final data = jsonDecode(response.body);
        return {
          'success': true,
          'data': data['data'] ?? data,
          'message': data['message'] ?? 'Donasi berhasil',
        };
      } else {
        final data = jsonDecode(response.body);
        return {
          'success': false,
          'message': data['message'] ?? 'Donasi gagal',
          'errors': data['errors'] ?? {},
        };
      }
    } catch (e) {
      print('Error donasi: $e');
      return {
        'success': false,
        'message': 'Terjadi kesalahan: $e',
      };
    }
  }

  Future<Map<String, dynamic>> donateQris({
    required int streamerId,
    required int nominal,
    required String pesan,
  }) async {
    try {
      final authController = Get.find<AuthController>();
      final token = authController.token.value;

      final response = await http.post(
        Uri.parse('$baseUrl/api/donasi/qris'),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          if (token.isNotEmpty) 'Authorization': 'Bearer $token',
        },
        body: jsonEncode({
          'streamer_id': streamerId,
          'nominal': nominal,
          'pesan': pesan,
        }),
      );

      print('=== QRIS DONASI RESPONSE ===');
      print('Status: ${response.statusCode}');
      print('Body: ${response.body}');

      if (response.statusCode == 200 || response.statusCode == 201) {
        final data = jsonDecode(response.body);
        return {
          'success': true,
          'data': data['data'],
        };
      } else {
        return {
          'success': false,
          'message': 'Gagal membuat QRIS',
        };
      }
    } catch (e) {
      return {
        'success': false,
        'message': 'Terjadi kesalahan: $e',
      };
    }
  }

  Future<Map<String, dynamic>> checkPaymentStatus(int donationId) async {
    try {
      final authController = Get.find<AuthController>();
      final token = authController.token.value;

      final response = await http.get(
        Uri.parse('$baseUrl/api/donasi/check-payment/$donationId'),
        headers: {
          'Accept': 'application/json',
          if (token.isNotEmpty) 'Authorization': 'Bearer $token',
        },
      );

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        return {
          'success': true,
          'data': data,
        };
      } else {
        return {
          'success': false,
          'message': 'Gagal cek status',
        };
      }
    } catch (e) {
      return {
        'success': false,
        'message': 'Terjadi kesalahan: $e',
      };
    }
  }

  Future<Map<String, dynamic>> simulateQris(int donationId) async {
    try {
      final authController = Get.find<AuthController>();
      final token = authController.token.value;

      final response = await http.get(
        Uri.parse('$baseUrl/api/donasi/simulate-qris/$donationId'),
        headers: {
          'Accept': 'application/json',
          if (token.isNotEmpty) 'Authorization': 'Bearer $token',
        },
      );

      if (response.statusCode == 200 || response.statusCode == 302) {
        return {
          'success': true,
        };
      } else {
        return {
          'success': false,
          'message': 'Gagal simulasi QRIS',
        };
      }
    } catch (e) {
      return {
        'success': false,
        'message': 'Terjadi kesalahan: $e',
      };
    }
  }

  Future<Map<String, dynamic>> getDonationHistory({int page = 1}) async {
    try {
      final authController = Get.find<AuthController>();
      final token = authController.token.value;

      final response = await http.get(
        Uri.parse('$baseUrl/api/donasi/history?page=$page'),
        headers: {
          'Accept': 'application/json',
          if (token.isNotEmpty) 'Authorization': 'Bearer $token',
        },
      );

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        return {
          'success': true,
          'data': data['donasi'] ?? data,
        };
      } else {
        return {
          'success': false,
          'message': 'Gagal mengambil riwayat',
        };
      }
    } catch (e) {
      return {
        'success': false,
        'message': 'Terjadi kesalahan: $e',
      };
    }
  }
}