/**
 * External dependencies
 */
import { ThemeProvider, useTheme } from '@emotion/react';

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Button } from '@wordpress/components';
import { useMemo, createInterpolateElement } from '@wordpress/element';
import { close as dismissIcon } from '@wordpress/icons';
import { useViewportMatch } from '@wordpress/compose';

/**
 * iThemes dependencies
 */
import { Text } from '@ithemes/ui';

/**
 * Internal dependencies
 */
import {
	BelowToolbarFill,
	EditCardsFill,
} from '@ithemes/security.dashboard.api';
import {
	useConfigContext,
	PromoCard,
} from '@ithemes/security.dashboard.dashboard';
import { LogoProWhite } from '@ithemes/security-style-guide';
import { FlexSpacer } from '@ithemes/security-components';
import { useLocalStorage } from '@ithemes/security-hocs';
import {
	StyledStellarSale,
	StyledStellarSaleButton,
	StyledStellarSaleContent,
	StyledStellarSaleLink,
	StyledStellarSaleGraphic,
	StyledStellarSaleHeading,
	StyledStellarSaleDismiss,
} from './styles';
import './style.scss';

export default function App() {
	const { installType } = useConfigContext();

	if ( installType === 'pro' ) {
		return null;
	}

	return (
		<>
			<BelowToolbarFill>
				{ ( { page, dashboardId } ) =>
					dashboardId > 0 && page === 'view-dashboard' && (
						<>
							<StellarSale />
							<Footer />
						</>
					)
				}
			</BelowToolbarFill>
			<EditCardsFill>
				<PromoCard title={ __( 'Trusted Devices', 'it-l10n-ithemes-security-pro' ) } />
				<PromoCard title={ __( 'Updates Summary', 'it-l10n-ithemes-security-pro' ) } />
				<PromoCard title={ __( 'User Security Profiles', 'it-l10n-ithemes-security-pro' ) } />
			</EditCardsFill>
		</>
	);
}

function Footer() {
	const [ isDismissed, setIsDismiss ] = useLocalStorage(
		'itsecPromoProUpgrade'
	);

	if ( isDismissed ) {
		return null;
	}

	return (
		<aside className="itsec-promo-pro-upgrade">
			<LogoProWhite />
			<section>
				<h2>
					{ __( 'Unlock More Security Features with Pro', 'it-l10n-ithemes-security-pro' ) }
				</h2>
				<p>
					{ __(
						'Go beyond the basics with premium features & support.',
						'it-l10n-ithemes-security-pro'
					) }
				</p>
			</section>
			<FlexSpacer />
			<a
				href="https://ithem.es/included-with-pro"
				className="itsec-promo-pro-upgrade__details"
			>
				{ __( 'What’s included with Pro?', 'it-l10n-ithemes-security-pro' ) }
			</a>
			<Button
				className="itsec-promo-pro-upgrade__button"
				href="https://ithem.es/go-security-pro-now"
			>
				{ __( 'Go Pro Now', 'it-l10n-ithemes-security-pro' ) }
			</Button>
			<Button
				icon="dismiss"
				className="itsec-promo-pro-upgrade__close"
				label={ __( 'Dismiss', 'it-l10n-ithemes-security-pro' ) }
				onClick={ () => setIsDismiss( true ) }
			/>
		</aside>
	);
}

const start = Date.UTC( 2023, 6, 24, 8, 0, 0 );
const end = Date.UTC( 2023, 7, 1, 8, 0, 0 );
const now = Date.now();

function StellarSale() {
	const isSmall = useViewportMatch( 'small' );
	const isHuge = useViewportMatch( 'huge' );
	const [ isDismissed, setIsDismiss ] = useLocalStorage(
		'itsecPromoStellarSale23'
	);
	const baseTheme = useTheme();
	const theme = useMemo( () => ( {
		...baseTheme,
		colors: {
			...baseTheme.colors,
			text: {
				...baseTheme.colors.text,
				white: '#F9FAF9',
			},
		},
	} ), [ baseTheme ] );

	if ( start > now || end < now ) {
		return null;
	}

	if ( isDismissed ) {
		return null;
	}

	return (
		<ThemeProvider theme={ theme }>
			<StyledStellarSale>
				<StyledStellarSaleContent isSmall={ isSmall }>
					<StyledStellarSaleHeading
						level={ 2 }
						variant="white"
						weight={ 300 }
						size="extraLarge"
						isSmall={ isSmall }
					>
						<strong>{ __( 'Make it yours.', 'it-l10n-ithemes-security-pro' ) }</strong>
						<br />
						{ __( 'Get $50 off the new Solid Foundations bundle.', 'it-l10n-ithemes-security-pro' ) }
					</StyledStellarSaleHeading>
					{ isSmall && (
						<Text
							variant="white"
							size="subtitleSmall"
							weight={ 300 }
							text={ createInterpolateElement(
								__( 'Purchase any StellarWP product during the sale and get <b>100%</b> off WP Business Reviews and take <b>40%</b> off all other brands.', 'it-l10n-ithemes-security-pro' ),
								{
									b: <strong />,
								}
							) }
						/>
					) }
					<StyledStellarSaleButton href="https://go.solidwp.com/security-plugin-sale" weight={ 600 }>
						{ __( 'Shop Now', 'it-l10n-ithemes-security-pro' ) }
					</StyledStellarSaleButton>
					<StyledStellarSaleLink
						as="a"
						href="https://go.solidwp.com/security-plugin-sale"
						variant="white"
						weight={ 700 }
						size="subtitleSmall"
						isSmall={ isSmall }
					>
						{ __( 'View all StellarWP Deals', 'it-l10n-ithemes-security-pro' ) }
					</StyledStellarSaleLink>
				</StyledStellarSaleContent>
				<StyledStellarSaleDismiss
					label={ __( 'Dismiss', 'it-l10n-ithemes-security-pro' ) }
					icon={ dismissIcon }
					onClick={ () => setIsDismiss( true ) }
				/>
				<StyledStellarSaleGraphic isHuge={ isHuge } />
			</StyledStellarSale>
		</ThemeProvider>
	);
}
