<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\NewsArticle>
 */
class NewsArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $titles = [
            'Pemerintah Luncurkan Program Bantuan Sosial Baru untuk Masyarakat',
            'Pengembangan Infrastruktur Digital di Seluruh Indonesia Dipercepat',
            'Inovasi Teknologi AI Tingkatkan Produktivitas Industri Nasional',
            'Festival Budaya Nusantara Akan Digelar di Jakarta Bulan Depan',
            'Kerjasama Internasional Buka Peluang Ekspor Produk Lokal',
            'Pendidikan Vokasi Menjadi Prioritas Pemerintah Tahun Ini',
            'Sektor Pariwisata Menunjukkan Pemulihan Signifikan Pasca Pandemi',
            'Investasi Energi Terbarukan Meningkat di Berbagai Daerah',
            'Program Beasiswa Pelajar Berprestasi Dibuka untuk Seluruh Indonesia',
            'Peluncuran Aplikasi Mobile untuk Kemudahan Layanan Publik',
            'Peningkatan Kualitas Kesehatan Masyarakat Melalui Program Puskesmas',
            'Kemitraan Strategis dengan Perusahaan Global Perkuat Ekonomi Digital',
            'Pembangunan Jalan Tol Baru Percepat Konektivitas Antar Wilayah',
            'Kampanye Lingkungan Hidup Ajak Masyarakat Kurangi Plastik',
            'Sertifikasi Halal Digital Permudah Pelaku Usaha UMKM',
            'Program Pelatihan Keahlian Digital untuk Tenaga Kerja Muda',
            'Pameran Industri Kreatif Hadirkan Ribuan Produk Inovatif',
            'Sistem Transportasi Publik Modern Mulai Beroperasi di Kota Besar',
            'Pengembangan Smart City Tingkatkan Kualitas Hidup Warga',
            'Kolaborasi Riset Nasional Hasilkan Temuan Ilmiah Baru',
            'Program Literasi Digital Sasar Masyarakat di Pelosok Daerah',
            'Peningkatan Ekspor Produk Pertanian ke Pasar Global',
            'Peluncuran Platform E-Commerce Lokal untuk UMKM',
            'Revitalisasi Pasar Tradisional Tingkatkan Ekonomi Lokal',
            'Program Vaksinasi Nasional Capai Target di Seluruh Provinsi',
            'Pengembangan Kawasan Industri Hijau Ramah Lingkungan',
            'Kerjasama Pendidikan dengan Universitas Terkemuka Dunia',
            'Implementasi Teknologi Blockchain dalam Sistem Administrasi Publik',
            'Festival Film Nasional Promosikan Karya Sineas Muda Indonesia',
            'Program CSR Perusahaan Bantu Pengembangan Desa Tertinggal',
        ];

        $contents = [
            'Pemerintah mengumumkan peluncuran program bantuan sosial yang dirancang untuk membantu masyarakat kurang mampu. Program ini mencakup berbagai bentuk bantuan seperti bantuan pangan, pendidikan, dan kesehatan. Dengan anggaran yang cukup besar, diharapkan program ini dapat menjangkau jutaan keluarga di seluruh Indonesia.',
            'Dalam upaya meningkatkan konektivitas digital, pemerintah mempercepat pembangunan infrastruktur jaringan internet di berbagai daerah. Proyek ini melibatkan pemasangan fiber optik dan menara BTS di daerah terpencil untuk memastikan akses internet yang merata bagi seluruh masyarakat Indonesia.',
            'Penerapan teknologi kecerdasan buatan (AI) semakin meluas di berbagai sektor industri nasional. Perusahaan-perusahaan mulai mengadopsi solusi AI untuk meningkatkan efisiensi operasional, mengoptimalkan produksi, dan memberikan layanan yang lebih baik kepada pelanggan. Transformasi digital ini diharapkan dapat meningkatkan daya saing Indonesia di pasar global.',
            'Festival budaya yang menampilkan keberagaman seni dan tradisi dari seluruh Nusantara akan segera digelar. Event ini menghadirkan berbagai pertunjukan tari, musik tradisional, pameran kerajinan, dan kuliner khas dari berbagai daerah. Festival ini menjadi wadah untuk melestarikan dan memperkenalkan kekayaan budaya Indonesia kepada generasi muda.',
            'Kerjasama bilateral dan multilateral dengan berbagai negara membuka peluang ekspor yang lebih luas bagi produk-produk lokal. Pemerintah terus mendorong pelaku usaha untuk memanfaatkan kesempatan ini dengan meningkatkan kualitas produk dan memenuhi standar internasional. Berbagai produk unggulan seperti kopi, tekstil, dan kerajinan tangan siap menembus pasar global.',
            'Program pendidikan vokasi diperkuat untuk menjawab kebutuhan industri akan tenaga kerja terampil. Pemerintah mengalokasikan dana khusus untuk pengembangan SMK dan politeknik dengan kurikulum yang relevan dengan kebutuhan pasar. Kerjasama dengan industri juga ditingkatkan untuk memastikan lulusan siap kerja.',
        ];

        $title = $this->faker->randomElement($titles);
        $content = $this->faker->randomElement($contents);
        
        // Add more paragraphs randomly
        for ($i = 0; $i < rand(2, 4); $i++) {
            $content .= "\n\n" . $this->faker->randomElement($contents);
        }

        return [
            'title' => $title,
            'slug' => Str::slug($title) . '-' . Str::random(6),
            'content' => $content,
            'image' => 'images/default-image.jpg',
            'user_id' => User::factory(),
            'is_internal' => false,
        ];
    }

    public function internal(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_internal' => true,
        ]);
    }
}
